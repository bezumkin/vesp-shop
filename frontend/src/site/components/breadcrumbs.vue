<template>
  <b-breadcrumb>
    <b-breadcrumb-item :to="{name: 'products'}">
      <fa icon="home" />
    </b-breadcrumb-item>
    <b-breadcrumb-item :to="$productLink(category)" :active="!product">
      {{ $translate(category.translations) }}
    </b-breadcrumb-item>
    <b-breadcrumb-item v-if="product" active>
      {{ $translate(product.translations) }}
    </b-breadcrumb-item>
  </b-breadcrumb>
</template>

<script>
export default {
  name: 'Breadcrumbs',
  props: {
    category: {
      type: Object,
      required: true,
    },
    product: {
      type: Object,
      default: null,
    },
  },
}
</script>
