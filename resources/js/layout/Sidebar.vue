<script setup>
import { ref,onBeforeMount, onMounted, onBeforeUnmount, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import SideHomeWhite from '../components/Icons/SideHomeWhite.vue'
import SideHomeBlack from '../components/Icons/SideHomeBlack.vue'
import AnalyticsBlackIcon from '@/assets/icons/analitics_black.svg'
import AnalyticsWhiteIcon from '@/assets/icons/sidebar/analytics_white.svg'
import PriceIcon from '@/assets/icons/sidebar/price.svg'
import PieceIcon from '@/assets/icons/sidebar/piece.svg'
import ServiceIcon from '@/assets/icons/sidebar/service.svg'
import ListBlackIcon from '@/assets/icons/list_black.svg'
import ListWhiteIcon from '@/assets/icons/sidebar/invoice_list_white.svg'
import LineBarWhiteHorizontal from '@/assets/icons/line_bar_white.svg'
import LineBarWhiteVertical from '@/assets/icons/line_bar_white_vertical.svg'
import UserBlackIcon from '@/assets/icons/sidebar/user_black.svg'
import UserWhiteIcon from '@/assets/icons/sidebar/user_white.svg'
import LogoutBlackIcon from '@/assets/icons/sidebar/logout_black.svg'
import LogoutModal from '../components/LogoutModal.vue'
import { useAuthStore } from "@/store/pinia/auth";
import { storeToRefs } from "pinia";
const { t } = useI18n();

let store = useStore()
let route = useRoute()

const logoutModalActive = ref(false)
const userAuthStore = useAuthStore();
const { role, user } = storeToRefs(useAuthStore());
const sideNavOpen = computed(() => {
  return store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen
});
const authStore = useAuthStore()
let toggleSubMenu = (e) => {
  // let parent = e.target.dataset.item;
  console.log(e.target)
}

const toggleModalActive = () => {
  logoutModalActive.value = !logoutModalActive.value
}

onBeforeMount(() => {
  // invoicesStore.fetchLocalData()
  authStore.fetchUser()
})

onMounted(() => {
  // authStore.fetchUser()
  window.addEventListener('resize', handleWindowResize)
})
// beforeDestroy
onBeforeUnmount(() => {
  window.removeEventListener('resize', handleWindowResize)
})

let handleWindowResize = () => {
  let sidenav = store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen

  // if (window.innerWidth >= 1200) {
  //   if (store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen) {
  //     store.commit('largeSidebar/toggleSidebarProperties')
  //   }
  // } else {
  //   if (!store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen) {
  //     store.commit('largeSidebar/toggleSidebarProperties')
  //   }
  // }
}
</script>
<template>
  <LogoutModal :modal-active="logoutModalActive" @toggle-modal-active="toggleModalActive" />
  <div class="side-content-wrap">
    <div class="side-content-wrap">
      <div :class="sideNavOpen === true ? 'open' : ''" class="sidebar-right pt-11">
        <!-- <perfect-scrollbar> -->
        <div>
          <div class="
                inline-flex
                items-center
                w-full
                px-4
                py-2
                text-xl
                font-bold
              " :class="sideNavOpen ? 'justify-end' : 'justify-start'">
            <div :class="sideNavOpen ? '' : 'hidden'">
              <p class="text-right">{{ user?.name }}</p>
              <!-- <p>{{ user?.email }}</p> -->
            </div>
            <div :class="sideNavOpen ? 'pl-3' : ''">
              <img class="avatar rounded-full" :src="`/images/faces/1.jpg`" alt="" />
            </div>

          </div>
        </div>
        <div class="flex flex-col justify-between h-[calc(100%-theme(space.11))] pb-11">
          <ul class="navigation-left" :class="sideNavOpen ? 'sidebar-opened' : 'sidebar-closed'">
            <router-link to="/dashboards" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex  items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">

                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.dashboard') }}</p>
                <div v-if="route.path === '/dashboards'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div :class="sideNavOpen ? 'animate-from-left' : 'animate-from-right'">
                    <SideHomeWhite />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <SideHomeBlack />
                </div>
              </div>
            </router-link>
            <router-link to="/invoices/list" tag="li" class="nav-item"
              :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.invoices') }}</p>
                <div v-if="route.path === '/invoices/list'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div>
                    <ListWhiteIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <ListBlackIcon />
                </div>
              </div>
            </router-link>

            <router-link v-if="user?.role==='admin'" to="/analytics" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.analytics') }}</p>
                <div v-if="route.path === '/analytics'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div>
                    <AnalyticsWhiteIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <AnalyticsBlackIcon />
                </div>
              </div>
            </router-link>

            <router-link v-if="user?.role==='admin'" to="/users" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.users') }}</p>
                <div v-if="route.path === '/users'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div>
                    <UserWhiteIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <UserBlackIcon />
                </div>
              </div>
            </router-link>

            <router-link v-if="user?.role==='admin'" to="/pieces/list" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.pieces') }}</p>
                <div v-if="route.path === '/pieces/list'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5 w-4'">
                  <div class="w-4">
                    <PieceIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center w-4' : 'ml-5 w-4 h-auto'">
                  <PieceIcon />
                </div>
              </div>
            </router-link>

            <router-link v-if="user?.role==='admin'" to="/services/list" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.services') }}</p>
                <div v-if="route.path === '/services/list'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div class="w-4">
                    <ServiceIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center w-4' : 'ml-5 w-4 h-auto'">
                  <ServiceIcon />
                </div>
              </div>
            </router-link>

            <router-link v-if="user?.role==='admin'" to="/prices" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
              <div class="nav-item-hold flex items-center py-2"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.prices') }}</p>
                <div v-if="route.path === '/prices'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <div class="w-4">
                    <PriceIcon />
                  </div>
                  <div>
                    <LineBarWhiteVertical v-if="sideNavOpen" />
                    <LineBarWhiteHorizontal v-else class="mt-1" />
                  </div>
                </div>
                <div v-else :class="sideNavOpen ? 'flex gap-1 items-center w-4' : 'ml-5 w-4 h-auto'">
                  <PriceIcon />
                </div>
              </div>
            </router-link>
            

          </ul>
          <div class="text-black relative">
            <ul class="navigation-left" :class="sideNavOpen ? 'sidebar-opened' : 'sidebar-closed'">
              <router-link to="/settings" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'">
                <div class="nav-item-hold flex  items-center py-2"
                  :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'">

                  <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.account') }}</p>
                  <div v-if="route.path === '/settings'" :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                    <div :class="sideNavOpen ? 'animate-from-left' : 'animate-from-right'">
                      <UserWhiteIcon />
                    </div>
                    <div>
                      <LineBarWhiteVertical v-if="sideNavOpen" />
                      <LineBarWhiteHorizontal v-else class="mt-1" />
                    </div>
                  </div>
                  <div v-else :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                    <UserBlackIcon />
                  </div>
                </div>
              </router-link>
              <!-- <router-link to="/sign-in" tag="li" class="nav-item" :class="sideNavOpen ? 'nav-opened' : 'nav-closed'"> -->
              <div class="nav-item-hold flex items-center py-2 cursor-pointer"
                :class="sideNavOpen ? 'justify-end  px-4' : 'justify-start'"
                @click="logoutModalActive = !logoutModalActive">
                <p v-if="sideNavOpen" class="font-readex font-normal text-sm mr-3">{{ t('sidebar.menu.signout') }}</p>
                <div :class="sideNavOpen ? 'flex gap-1 items-center' : 'pl-5'">
                  <LogoutBlackIcon />
                </div>
              </div>
              <!-- </router-link> -->
            </ul>
          </div>
        </div>

        <!-- </perfect-scrollbar> -->
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.nav-item.router-link-exact-active {
  &.nav-opened {
    @apply rounded-2xl;
  }

  &.nav-closed {}

  @apply text-white bg-main;

  &:after {
    content: '';
    position: absolute;
    width: 30px;
    height: 30px;
    bottom: -15px;
    right: -15px;
    transform: rotate(45deg);
  }

  // transition: all 0.4s ease-in-out;
}

.submenuLi {
  &:hover {
    .submenuli-icon {
      color: #8b5cf6;
    }
  }

  .submenuli-icon {
    color: #9ca3af;
  }
}

.submneu-nested-link {
  padding: 0 !important;
  color: #000 !important;

  &:hover {
    background-color: transparent !important;
    color: #8b5cf6 !important;
  }
}

.side-content-wrap {
  .sidebar-right {
    position: fixed;
    top: 0;
    right: -180px;
    width: 240px;
    height: 100vh;
    background: #fff;
    box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06),
      0 1px 4px rgba(0, 0, 0, 0.08);
    z-index: 90;
    transition: all 0.4s ease-in-out;

    @media screen and (max-width: 991px) {
      right: -240px;
    }

    &.open {
      right: 0;
      transition: all 0.4s ease-in-out;

    }

    .ps {
      height: calc(100vh - 64px);
    }

    .navigation-left {
      list-style: none;
      text-align: center;
      width: 100%;
      // height: 100%;
      margin: 4px 0;

      &.sidebar-opened {
        padding: 0 1rem;
      }

      &.sidebar-closed {}


      .nav-item,
      .nav-item-single {
        position: relative;
        display: block;
        width: 100%;
        overflow: hidden;
        cursor: pointer;

        &:hover {
          .nav-item-hold {}

          &:after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            bottom: -15px;
            right: -15px;
            transform: rotate(45deg);
            // @apply bg-purple-500;
          }
        }

        &.active {
          @apply text-purple-500;

          &:after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            bottom: -15px;
            right: -15px;
            transform: rotate(45deg);
            @apply bg-purple-500;
          }
        }

        border-bottom: 1px solid #dee2e6;

        .nav-item-hold {
          width: 100%;
          margin: 4px 0;
          height: 44px;

          span.material-icons {
            font-size: 2rem;
          }
        }
      }
    }
  }

  .sidebar-left-secondary {
    position: fixed;
    top: 80px;
    left: calc(-220px - 20px);
    z-index: 89;
    height: calc(100vh - 80px);
    width: 220px;
    padding: 0.75rem 0;
    transition: all 0.24s ease-in-out;
    background: #fff;

    &.open {
      left: 120px;
      transition: all 0.24s ease-in-out;
    }

    ul.childNav {
      li {
        &.dropdown-sidemenu {
          display: block;
          transition: all 0.3s ease-in;

          &.open {
            a {
              .dd-arrow {
                transform: rotate(90deg);
                transition: all 0.3s ease-in;
              }
            }

            ul.submenu {
              display: block;
              max-height: 1000px;
              transition: all 0.3s ease-in;
            }
          }

          a {
            .dd-arrow {
              margin-left: auto !important;
              transition: all 0.3s ease-in;
            }
          }
        }

        // &.active {
        //     a {
        //         background-color: #f3f4f6;
        //         @apply text-purple-500;
        //     }
        // }
        a {
          text-transform: capitalize;
          display: flex;
          align-items: center;
          font-size: 13px;
          cursor: pointer;
          padding: 12px 24px;
          transition: 0.15s all ease-in;

          &:hover {
            background-color: #f3f4f6;
            @apply text-purple-500;
          }

          &.router-link-active.router-link-exact-active {
            @apply text-purple-500;
          }
        }

        ul.submenu {
          @apply bg-gray-50;
          display: none;
          max-height: 0px;
          transition: all 0.3s ease-in;

          &.open {
            display: block;
            transition: all 0.3s ease-in;
          }

          li {
            a {
              padding-left: 48px;
            }
          }
        }
      }
    }
  }

  .sidebar-overlay {
    display: none;
    position: fixed;
    width: calc(100% - 120px - 220px);
    height: calc(100vh - 80px);
    bottom: 0;
    right: 0;
    background: rgba(0, 0, 0, 0);
    z-index: 101;
    cursor: w-resize;

    &.open {
      display: block;
    }
  }

  .animate-from-left {
    animation-name: example;
    animation-duration: 4s;
    transition: all 0.3s linear;
  }

  @keyframes example {}
}
</style>
