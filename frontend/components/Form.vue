<template>
  <div class="w-full max-w-xs m-auto text-center">
    <form @submit.prevent="submit">
      <div class="mb-6">
        <label
          for="url"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
          >Enter your url here...</label
        >
        <input
          id="text"
          v-model="url"
          type="url"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="https://example.url"
          required
        />
      </div>

      <div v-if="newUrl" class="pb-5">
        <div
          class="p-2 bg-indigo-800 items-center text-indigo-100 leading-none lg:rounded-full flex lg:inline-flex"
          role="alert"
        >
          <span
            class="flex rounded-full bg-indigo-500 uppercase px-2 py-1 text-xs font-bold mr-3"
            >New</span
          >
          <span class="font-semibold mr-2 text-left flex-auto">{{
            newUrl
          }}</span>
        </div>
      </div>

      <button
        v-if="!newUrl"
        type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Generate new url
      </button>
    </form>
  </div>
</template>
<script>
export default {
  name: 'FormPage',

  data() {
    return {
      url: null,
      newUrl: null,
    }
  },

  watch: {
    url() {
      if (this.newUrl) {
        this.url = null
        this.newUrl = null
      }
    },
  },

  methods: {
    async submit() {
      this.isLoading = true

      const response = await fetch(`${process.env.BACKEND_URL}/api/generate`, {
        method: 'POST',
        body: new URLSearchParams({
          originalLink: this.url,
        }),
      })

      this.isLoading = false

      this.newUrl = await response.text()
    },
  },
}
</script>
