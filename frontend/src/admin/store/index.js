import {hasScope} from '@vesp/frontend/utils'
import menuItems from '../plugins/menu'

export const state = () => ({
  menu: menuItems,
  languages: [],
})

export const mutations = {
  menu(state, payload) {
    state.menu = payload
  },
  languages(state, payload) {
    state.languages = payload
  },
}

export const getters = {
  menu({menu, auth}) {
    const filtered = []
    if (auth.loggedIn) {
      for (let item of menu) {
        item = JSON.parse(JSON.stringify(item))
        if (item.children) {
          item.children = item.children.filter((child) => !child.scope || hasScope(child.scope, auth))
        }
        if ((!item.scope || hasScope(item.scope, auth)) && (item.children === undefined || item.children.length)) {
          filtered.push(item)
        }
      }
    }
    return filtered
  },
}

export const actions = {
  async languages({commit, state}) {
    if (!state.languages.length) {
      try {
        const {data} = await this.$axios.get('admin/languages', {params: {combo: true}})
        commit('languages', data.rows)
      } catch (e) {}
    }
    return state.languages
  },
}
