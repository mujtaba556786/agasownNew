<div
    class="w-full flex justify-between  px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 max-1180:px-[30px]">
    {{--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    --}}
    {{-- Left Nagivation Section --}}
    <div class="flex items-center">
        <v-desktop-category-menu>
            <span></span>
            <span></span>
            <span></span>
        </v-desktop-category-menu>
        <!-- <v-desktop-category>
            <div class="flex  items-center">
                <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
                <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
                <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
            </div>
        </v-desktop-category>-->
    </div>
    <div class="flex items-center">
        <a href="{{ route('shop.home.index') }}" class="place-self-start " aria-label="Agasown">
            <img src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}" width="120"
                alt="Agasown">
        </a>
    </div>

    {{-- Right Nagivation Section --}}
    <div class="flex  items-center ">
        {{-- Search Bar Container --}}

        {{-- Right Navigation Links --}}
        <div class="flex gap-x-[15px] ">
            {{-- Compare --}}
            @if (core()->getConfigData('general.content.shop.compare_option'))
                <a href="{{ route('shop.compare.index') }}" aria-label="Compare">
                    <span class="icon-compare inline-block text-[24px] cursor-pointer"></span>
                </a>
            @endif

            {{-- Mini cart --}}
            @include('shop::checkout.cart.mini-cart')

            {{-- user profile --}}
            <x-shop::dropdown
                position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span class="icon-users inline-block text-[24px] cursor-pointer"></span>
                </x-slot:toggle>

                {{-- Guest Dropdown --}}
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-[10px]">
                            <p class="text-[20px] font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>

                            <p class="text-[14px]">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-[12px] py-2px border border-[#3F183B]"></p>

                        <div class="flex gap-[16px] mt-[25px]">
                            <a href="{{ route('shop.customer.session.create') }}"
                                class="primary-button block w-max px-[29px] mx-auto m-0 ml-[0px] rounded-[18px] text-base text-center">
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button block w-max m-0 ml-[0px] mx-auto px-[29px] border-2 rounded-[18px] text-base text-center">
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>
                        </div>
                    </x-slot:content>
                @endguest

                {{-- Customers Dropdown --}}
                @auth('customer')
                    <x-slot:content class="!p-[0px]">
                        <div class="grid gap-[10px] p-[20px] pb-0">
                            <p class="text-[20px] font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-[14px]">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-[12px] py-2px border border-[#3F183B]"></p>

                        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                            <a class="px-5 py-2 text-[16px] hover:navyBlue cursor-pointer"
                                href="{{ route('shop.customers.account.profile.index') }}">
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a class="px-5 py-2 text-[16px] hover:navyBlue cursor-pointer"
                                href="{{ route('shop.customers.account.orders.index') }}">
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                <a class="px-5 py-2 text-[16px] hover:navyBlue cursor-pointer"
                                    href="{{ route('shop.customers.account.wishlist.index') }}">
                                    @lang('shop::app.components.layouts.header.wishlist')
                                </a>
                            @endif

                            {{-- Customers logout --}}
                            @auth('customer')
                                <x-shop::form method="DELETE" action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout">
                                </x-shop::form>
                                <a class="px-5 py-2 text-[16px] hover:navyBlue cursor-pointer"
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                                    @lang('shop::app.components.layouts.header.logout')
                                </a>
                            @endauth
                        </div>
                    </x-slot:content>
                @endauth
            </x-shop::dropdown>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-category-menu-template">
        <x-shop::dropdown position="bottom-left">
            <!-- Dropdown Toggler -->
            <x-slot:toggle>
                <div class="flex gap-[10px] cursor-pointer" >
                    <x-css-menu>
                        <span></span>
                        <span></span>
                        <span></span>
                    </x-css-menu>
                </div>
            </x-slot:toggle>
            <!-- Dropdown Content -->
            <x-slot:content class="!p-[0px]">
                <div class="flex gap-[20px] items-center pb-[21px]" v-if="isLoading">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="container px-6 py-6" v-else>
                    <div>
                        <ul v-for="category in categories">
                            <li class="relative border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue">
                                <a
                                :href="category.url"
                                class="px-[20px] uppercase"
                                v-text="category.name">
                                </a>
                            </li>      
                            <!--<ul>
                                <li class="content-start w-full flex-auto"
                                    v-for="pairCategoryChildren in pairCategoryChildren(category)">
                                    <template v-for="secondLevelCategory in pairCategoryChildren">
                                        <p class="text-navyBlue font-medium">
                                            <a
                                                :href="secondLevelCategory.url"
                                                v-text="secondLevelCategory.name"
                                            >
                                            </a>
                                        </p>
                                    </template>    
                                </li>
                            </ul>-->
                        </ul>
                    </div>
                </div>
            </x-slot:content>
        </x-shop::dropdown>
    </script>


    <script type="text/x-template" id="v-desktop-category-template">
        <div
            class="flex gap-[20px] items-center pb-[21px]"
            v-if="isLoading"
        >
            <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
            <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
            <span class="shimmer w-[80px] h-[24px] rounded-[4px]"></span>
        </div>

        <div
            class="flex items-center"
            v-else
        >
            <div
                class="relative group border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue"
                v-for="category in categories"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block  px-[20px] uppercase"
                        v-text="category.name"
                    >
                    </a>
                </span>

                <div
                    class="w-max absolute top-[49px] max-h-[580px] max-w-[1260px] p-[35px] z-[1] overflow-auto overflow-x-auto bg-white shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] border border-b-0 border-l-0 border-r-0 border-t-[1px] border-[#3F183B] pointer-events-none opacity-0 transition duration-300 ease-out translate-y-1 group-hover:pointer-events-auto group-hover:opacity-100 group-hover:translate-y-0 group-hover:ease-in group-hover:duration-200 ltr:-left-[35px] rtl:-right-[35px]"
                    v-if="category.children.length"
                >
                    <div class="flex aigns gap-x-[70px] justify-between">
                        <div
                            class="grid grid-cols-[1fr] gap-[20px] content-start w-full flex-auto min-w-max max-w-[150px]"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="text-navyBlue font-medium">
                                    <a
                                        :href="secondLevelCategory.url"
                                        v-text="secondLevelCategory.name"
                                    >
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-[12px]"
                                    v-if="secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-[14px] font-medium text-[#3F183B]"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a
                                            :href="thirdLevelCategory.url"
                                            v-text="thirdLevelCategory.name"
                                        >
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="module">
        app.component('v-desktop-category-menu', {
            template: '#v-desktop-category-menu-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;
                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                },
                toggleMenu() {
                    this.isMenuOpen = !this.isMenuOpen;
                },
                showCategories() {
                    this.isMenuOpen = true;
                },
                hideCategories() {
                    this.isMenuOpen = false;
                }
            },
        });
    </script>
    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return {
                    isLoading: true,

                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;

                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                }
            },
        });
    </script>
@endPushOnce
