/**
 * product service api goes here
 */
const ProductService = {
  async read() {
    try {
      const response = await fetch("/product.php");
      return await response.json();
    } catch {
      return [];
    }
  },

  /**
   * @param {FormData} data
   */
  async create(data) {
    return await fetch("/product.php", { method: "POST", body: data });
  },

  /**
   * @param {number} id
   * @param {FormData} data
   */
  async update(id, data) {
    data.append("_method", "PUT");
    return await fetch(`/product.php?id=${id}`, { method: "POST", body: data });
  },

  async delete(id) {
    return await fetch(`/product.php?id=${id}`, { method: "DELETE" });
  },
};

/**
 * Init app
 */
new Vue({
  el: "#app",

  data: {
    isShowCreateModal: false,
    createFormLoading: false,
    createForm: {
      name: "",
      unit: "",
      price: null,
      expires_at: "",
      available_inventory: null,
      file: undefined,
    },

    isShowUpdateModal: false,
    updateRecordIdx: 0,
    updateFormLoading: false,
    updateForm: {
      id: 0,
      name: "",
      unit: "",
      price: null,
      expires_at: "",
      available_inventory: null,
      file: undefined,
    },

    records: [],
  },

  mounted() {
    ProductService.read().then((data) => {
      this.records = data;
    });
  },

  methods: {
    showCreateModal() {
      this.isShowCreateModal = true;
    },

    hideCreateModal() {
      this.isShowCreateModal = false;
      this.createForm = { ...this.resetModal() };
    },

    async submitCreateModal() {
      const formData = new FormData();

      Object.entries(this.createForm).forEach(([key, value]) => {
        if (key === "file") {
          formData.append(key, value[0]);
          return;
        }

        formData.append(key, value);
      });

      this.createFormLoading = true;

      try {
        const response = await ProductService.create(formData);
        const data = await response.json();

        if (response.ok) {
          this.records.push(data.data.product);
          this.hideCreateModal();
          return;
        }

        alert(data.message);
      } catch (e) {
        alert(e.message);
      }

      this.createFormLoading = false;
    },

    showUpdateModal(idx) {
      this.isShowUpdateModal = true;
      this.updateForm = { ...this.records[idx] };
      this.updateRecordIdx = idx;
    },

    hideUpdateModal() {
      this.isShowUpdateModal = false;
    },

    async submitUpdateModal() {
      const formData = new FormData();

      Object.entries(this.updateForm).forEach(([key, value]) => {
        if (key === "file") {
          formData.append(key, value[0]);
          return;
        }

        formData.append(key, value);
      });

      this.updateFormLoading = true;

      try {
        const response = await ProductService.update(this.records[this.updateRecordIdx].id, formData);
        const data = await response.json();

        if (response.ok) {
          console.log(data.data.product);
          this.$set(this.records, this.updateRecordIdx, data.data.product);
          this.hideUpdateModal();
          return;
        }

        alert(data.message);
      } catch (e) {
        alert("Can't update this record. Please check your inputs and make sure the image size is small.");
      }

      this.updateFormLoading = false;
    },

    resetModal() {
      return {
        name: "",
        unit: "",
        price: null,
        expires_at: "",
        available_inventory: null,
        file: undefined,
      };
    },

    showDeleteModal(idx) {
      if (!confirm(`Delete this record (${this.records[idx].name})?`)) return;

      ProductService.delete(this.records[idx].id).catch(() => {});

      this.$delete(this.records, idx);
    },

    formatToLongDate(date) {
      return new Date(date).toLocaleString("default", { year: "numeric", month: "long", day: "numeric" });
    },

    sumAvailableInventory(price, count) {
      return count * price;
    },
  },
});
