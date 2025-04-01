<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3"

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
            <v-card-text class="text-center text-h4 font-weight-medium">Sign up for an account</v-card-text>
            <v-form  lazy-validation >
                <div class="text-subtitle-1 text-medium-emphasis">Username</div>
                <v-text-field
                    density="compact"
                    placeholder="Username"
                    prepend-inner-icon="mdi-account-outline"
                    variant="outlined"
                />

                <div class="text-subtitle-1 text-medium-emphasis">Email</div>
                <v-text-field
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    type="email"
                />

                <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">Password
                </div>
                <v-text-field
                    :type="visible ? 'text' : 'password'"
                    density="compact"
                    placeholder="Enter your password"
                    prepend-inner-icon="mdi-lock-outline"
                    variant="outlined"
                >
                    <template v-slot:append-inner>
                        <v-icon
                            :icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                            @click="visible = !visible"
                            tabindex="-1"
                        />
                    </template>
                </v-text-field>

                <v-text-field
                    type="password"
                    density="compact"
                    placeholder="Confirm your password"
                    prepend-inner-icon="mdi-lock-outline"
                    variant="outlined"
                />

                <v-btn
                    class="my-3"
                    color="blue"
                    size="large"
                    variant="tonal"
                    block
                    type="submit"
                    text="Sign Up"
                />
            </v-form>


            <v-card-text class="text-center">
                Already have an account?
                <Link
                    class="text-blue text-decoration-none"
                    href="/login"
                >
                    Log in
                    <v-icon icon="mdi-chevron-right"></v-icon>
                </Link>
            </v-card-text>
        </v-card>
    </div>

</template>

