<?php
require_once "services/DotEnvService.php";

(new DotEnvService(__DIR__ . "/.env"))->load();

$app_name = (string)getenv("APP_NAME");
$app_version = (string)getenv("APP_VERSION");

// Create affsub4 if it doesn't exist already
$aff_sub4 = $_COOKIE["aff_sub4"] ?? null;
if ($aff_sub4 == null) {
    $uid = bin2hex(random_bytes(16));
    $expires_at = time() + 60 * 60 * 24 * 30; // Expires in 30 days
    setcookie("aff_sub4", $uid, $expires_at, "/", "", false, false);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $app_name ?></title>
    <link rel="stylesheet" href="./assets/css/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.0/dist/cdn.min.js"></script>
</head>
<body>
<div class="bg-gray-900 min-h-screen w-full flex items-center justify-center" x-data>
    <div class="w-full px-8 md:px-20 lg:px-14 xl:px-0 mx-auto max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen">
        <img src="assets/imgs/ig-interaction.png" alt="" class="w-14 mx-auto animate-bounce">
        <h1 class="text-white font-semibold text-center text-2xl mb-4">
            <?= $app_name ?> - V<?= $app_version ?>
        </h1>
        <section class="bg-gray-700 w-full rounded px-6 py-10 shadow-lg shadow-blue-500/20">
            <div x-show="$store.form.errors.length >= 1" class="w-full">
                <template x-for="error in $store.form.errors">
                    <div class="bg-red-400 text-white rounded px-3 py-2 mb-3">
                        <p x-text="error" class="font-semibold"></p>
                    </div>
                </template>
            </div>

            <!-- Loading -->
            <div x-show-="$store.form.loading" class="flex items-center justify-center w-full my-4">
                <svg class="animate-spin h-10 w-10 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Part one -->
            <form @submit.prevent="$store.form.getFollowersPage()" x-show="$store.form.page === 0">
                <div class="flex flex-col mb-5">
                    <label for="username" class="text-white font-semibold mb-2">
                        Username
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="bg-gray-900 rounded py-2 px-2 text-white" id="username" placeholder="Ex: johndoe" x-model="$store.form.data.username" required>
                </div>

                <div class="mb-5">
                    <h3 class="font-semibold text-white mb-2">Select a platform</h3>
                    <div class="bg-gray-900 text-white rounded px-3 py-2 w-full border border-gray-800 text-center hover:cursor-pointer mb-4" :class="{'border border-purple-400': $store.form.data.platform === 'instagram'}" @click="$store.form.setPlatform('instagram')">
                        <i class="ti ti-brand-instagram"></i> <span class="font-semibold text-lg">Instagram</span>
                    </div>
                    <div class="bg-gray-900 text-white rounded px-3 py-2 w-full border border-gray-800 text-center hover:cursor-pointer" :class="{'border border-purple-400': $store.form.data.platform === 'tiktok'}" @click="$store.form.setPlatform('tiktok')">
                        <i class="ti ti-brand-tiktok"></i> <span class="font-semibold text-lg">TikTok</span>
                    </div>
                </div>

                <button type="submit" class="bg-purple-500 hover:bg-purple-700 transition-all duration-200 ease-in-out text-white font-semibold w-full rounded py-2">
                    Continue
                </button>
            </form>

            <!-- Part two -->
            <form @submit.prevent="$store.form.getOffersPage()" x-show="$store.form.page === 1" class="flex flex-col gap-y-4">
                <div class="text-center">
                    <h3 class="font-semibold text-white text-xl">Select Amount of Followers</h3>
                    <small class="text-white/80">The more followers you want, the more offers you have to complete</small>
                </div>
                <div class="bg-gray-900 text-white rounded px-3 py-2 w-full border border-gray-800 text-center hover:cursor-pointer" :class="{'border border-purple-400': $store.form.data.amount === 250}" @click="$store.form.setFollowerAmount(250)">
                    <i class="ti ti-users"></i> <span class="font-semibold text-lg">250</span>
                </div>
                <div class="bg-gray-900 text-white rounded px-3 py-2 w-full border border-gray-800 text-center hover:cursor-pointer" :class="{'border border-purple-400': $store.form.data.amount === 500}" @click="$store.form.setFollowerAmount(500)">
                    <i class="ti ti-users"></i> <span class="font-semibold text-lg">500</span>
                </div>
                <div class="bg-gray-900 text-white rounded px-3 py-2 w-full border border-gray-800 text-center hover:cursor-pointer" :class="{'border border-purple-400': $store.form.data.amount === 1000}" @click="$store.form.setFollowerAmount(1000)">
                    <i class="ti ti-users"></i> <span class="font-semibold text-lg">1,000</span>
                </div>

                <button type="submit" class="bg-purple-500 hover:bg-purple-700 transition-all duration-200 ease-in-out text-white font-semibold w-full rounded py-2">
                    Continue
                </button>
            </form>

            <!-- Part three -->
            <div x-show="$store.form.page === 2 && !$store.form.loading">
                <div class="flex flex-col gap-y-4 mb-4 max-h-[500px] overflow-y-auto">
                    <template x-for="offer in $store.form.offers">
                        <div @click="$store.form.openUrl(offer)" class="rounded p-2 flex items-center justify-between bg-gray-900 hover:bg-gray-800 transition-all duration-200 ease-in-out cursor-pointer">
                            <div class="flex items-center">
                                <img x-bind:src="offer.picture" x-bind:alt="offer.name_short" class="min-w-14 w-14 h-14 object-cover overflow-hidden rounded mr-2">
                                <div class="flex flex-col">
                                    <h3 x-text="offer.name_short" class="text-white font-semibold text-xl"></h3>
                                    <p x-text="offer.adcopy" class="text-white/80"></p>
                                </div>
                            </div>
                            <div class="text-white font-xl p-2">
                                <i class="ti ti-external-link"></i>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col items-center justify-center">
                    <p class="font-semibold text-white mb-3">
                        Complete <span x-text="$store.form.calculateConversionsRequired()"></span> offer to continue
                    </p>
                    <svg class="animate-spin h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>