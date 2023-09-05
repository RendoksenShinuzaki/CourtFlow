<template>
    <div class="flex items-center min-h-screen bg-white dark:bg-white">
    <div class="container mx-auto">
        <div class="max-w-md mx-auto my-10">
            <div class="text-center">
                <h1 class="my-3 text-3xl font-semibold text-gray-700 dark:text-black">Sign in</h1>
                <p class="text-gray-500 dark:text-black">Sign in to access your account</p>
            </div>
            <div class="m-7">
                <form action="">
                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm text-black dark:text-black">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="you@company.com" class="w-full px-3 py-2 placeholder-black border border-black rounded-md focus:outline-none" />
                    </div>
                    <div class="mb-6">
                        <div class="flex justify-between mb-2">
                            <label for="password" class="text-sm text-black dark:text-black">Password</label>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Your Password" class="w-full px-3 py-2 placeholder-black border border-black rounded-md focus:outline-none" />
                    </div>
                    <div class="mb-6">
                        <button type="button" class="w-full px-3 py-4 text-white bg-indigo-950 rounded-md focus:bg-indigo-600 focus:outline-none">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script setup>

</script>

<style scoped>

</style>