<template>
    <div class="px-6 mx-auto">
        <div class="bg-white dark:bg-gray-800 dark:border-gray-600  rounded-lg border">
            <Commands />
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetDialogModal from "@@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@@/Jetstream/SecondaryButton.vue";
import JetButton from "@@/Jetstream/Button.vue";
import JetInput from "@@/Jetstream/Input.vue";
import JetInputError from "@@/Jetstream/InputError.vue";
import JetLabel from "@@/Jetstream/Label.vue";
import Commands from "./Commands/App.vue";
import AppLayout from "@@/Layouts/AppLayout.vue";

export default defineComponent({
    layout: AppLayout,
    components: {
        JetDialogModal,
        JetSecondaryButton,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        Commands,
    },
});
</script>
