<?php

namespace App;

use App\Product;
use App\HasFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController
{
    use HasFile;

    /**
     * @var JsonResponse|null
     */
    protected $response = null;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $method = $this->request->getMethod();

        if ($method == 'GET') {
            $this->response = $this->index();
            return;
        }

        $contentMethod = \strtoupper($this->request->request->get('_method', ''));

        // update
        if ($contentMethod == 'PUT' || $contentMethod == 'PATCH' || $method == 'PUT' || $method == 'PATCH') {
            $this->response = $this->update();
            return;
        }

        // delete
        if ($contentMethod == 'DELETE' || $method == 'DELETE') {
            $this->response = $this->destroy();
            return;
        }

        // store
        if ($method == 'POST') {
            $this->response = $this->store();
            return;
        }
    }

    public function response()
    {
        if (!$this->response instanceof Response) {
            $this->response = new JsonResponse($this->response, 200);
        }

        $this->response->prepare($this->request);
        $this->response->send();
    }

    public function index()
    {
        return Product::all();
    }

    public function store()
    {
        /**
         * assuming the file is an image with decent size and resolution
         *
         * @var UploadedFile|null
         */
        $image = $this->request->files->get('file');

        try {
            $imagePublicPath = $this->storeImage($image);
        } catch (FileException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }

        $product = Product::create($this->request->request->all() + [
            'image_path' => $imagePublicPath
        ]);

        return new JsonResponse([
            'message' => 'Product created successfully.',
            'data' => ['product' => $product],
        ]);
    }

    public function update()
    {
        $id = $this->request->query->get('id');

        $product = Product::find($id);
        if (!$product) {
            return new JsonResponse('', 400);
        }

        $image = $this->request->files->get('file');
        $data = $this->request->request->all();

        if ($image) {
            if ($product->image_path) {
                $this->deleteImage($product->image_path);
            }

            $data['image_path'] = $this->storeImage($image);
        }

        $product->update($data);

        return new JsonResponse([
            'message' => 'Product updated successfully.',
            'data' => ['product' => $product],
        ]);
    }

    public function destroy()
    {
        $id = $this->request->query->get('id');

        $product = Product::find($id);

        if ($product) {
            if ($product->image_path) {
                $this->deleteImage($product->image_path);
            }

            $product->delete();
        }

        return new JsonResponse('', 204);
    }
}
