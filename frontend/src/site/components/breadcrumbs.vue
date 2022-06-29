<template>
  <b-breadcrumb>
    <b-breadcrumb-item :to="{name: 'index'}">
      <fa icon="home" />
    </b-breadcrumb-item>
    <b-breadcrumb-item :to="{name: 'category', params: {category: category.alias}}" :active="!product">
      {{ category.title }}
    </b-breadcrumb-item>
    <b-breadcrumb-item
      v-if="product"
      :to="{name: 'category-product', params: {category: category.alias, product: product.alias}}"
      active
    >
      {{ product.title }}
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
