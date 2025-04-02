<script setup>
import {ref} from "vue";
import {Link, useForm} from "@inertiajs/vue3";

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('login');
};

const visible = ref(false);
</script>

<template>

    <div class="pt-8 mt-8">
        <v-card
            class="mx-auto px-12 pb-8 pt-6 mt-8"
            elevation="8"
            max-width="480"
            rounded="lg"
        >
            <v-card-text class="text-center text-h4 font-weight-medium">Log in to your account</v-card-text>

            <v-form lazy-validation @submit.prevent="submit">
                <div class="text-subtitle-1 text-medium-emphasis">Account</div>
                <v-text-field
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    type="email"
                    v-model="form.email"
                    :error-messages="form.errors.email ?? form.errors.email"
                />

                <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">Password</div>
                <v-text-field
                    :type="visible ? 'text' : 'password'"
                    density="compact"
                    placeholder="Enter your password"
                    prepend-inner-icon="mdi-lock-outline"
                    variant="outlined"
                    v-model="form.password"
                    :error-messages="form.errors.password ?? form.errors.password"
                >
                    <template v-slot:append-inner>
                        <v-icon
                            :icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                            @click="visible = !visible"
                            tabindex="-1"
                        />
                    </template>
                </v-text-field>

                <v-btn
                    class="my-3"
                    color="blue"
                    size="large"
                    variant="tonal"
                    block
                    type="submit"
                    text="Log In"
                />
            </v-form>

            <v-card-text class="text-center">
                Don't have an account?
                <Link
                    class="text-blue text-decoration-none"
                    href="/register"
                >
                    Sign up
                    <v-icon icon="mdi-chevron-right"></v-icon>
                </Link>
            </v-card-text>
        </v-card>
    </div>

</template>

