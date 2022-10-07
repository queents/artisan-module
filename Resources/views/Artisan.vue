<script setup>
import JetDialogModal from "@@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@@/Jetstream/SecondaryButton.vue";
import JetButton from "@@/Jetstream/Button.vue";
import JetInput from "@@/Jetstream/Input.vue";
import JetInputError from "@@/Jetstream/InputError.vue";
import JetLabel from "@@/Jetstream/Label.vue";
import Header from "@@/Layouts/Includes/Header.vue";
import Commands from "./Commands/App.vue";
import {createToaster} from "@meforma/vue-toaster";
import {computed, onMounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useTrans} from "@@/Composables/useTrans";

const props = defineProps({
    rows: Object,
    roles: Object,
    render: Object,
    list: Object,
    data: Object,
});

const toaster = createToaster({ /* options */});


/*
Actions / Modals Data
 */
let actionModal = ref({});
let modalAction = ref({});
let selectedID = ref(null);
let errors = ref({});
let form = ref({});



/*
Methods
 */
function fireAction (name, id = null){
    Inertia.post(route(name), {
        id: id ? id : selectedID.value,
    });
}

function openModal(name, id = null){
    selectedID.value = id;
    actionModal.value[name] = !actionModal.value[name];
}

function modalActionRun(modal, action) {
    if (selectedID.value) {
        modalAction.value[modal].id = selectedID.value;
    }
    let form = useForm(modalAction.value[modal]);
    Inertia.post(route(action), form,{
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            actionModal.value[modal] = false;
            success();
        },
    });
}

function openUrl(url){
    window.open(url);
}

/*
Fire Success Response / Error Response
 */

const getMessage = computed({
    get() {
        return props.data.message
    },
    set(value) {
        return value;
    },
});
const success = () => {
    const message = getMessage.value;
    if (typeof message === 'object') {
        if (message.type === 'error') {
            toaster.error(message.message, {
                position: 'top',
            });
        } else if (message.type === 'success') {
            toaster.success(message.message, {
                position: 'top',
            });
        } else {
            toaster.success(message.message, {
                position: 'top',
            });
        }
    } else {
        toaster.success(message, {
            position: 'top',
        });
    }
}

// Load Components
const modals = computed(() => {
    let modalsArray = [];
    if(props.render.components){
        for (let i = 0; i < props.render.components.length; i++) {
            if(props.render.components[i].classType === 'modal'){
                modalsArray.push(props.render.components[i]);
            }
        }
    }

    return modalsArray;
});
const widgets = computed(() => {
    let widgetsArray = [];
    if(props.render.components) {
        for (let i = 0; i < props.render.components.length; i++) {
            if (props.render.components[i].classType === 'widget') {
                widgetsArray.push(props.render.components[i]);
            }
        }
    }
    return widgetsArray;
});
const actions = computed(() => {
    let actionsArray = [];
    if(props.render.components) {
        for (let i = 0; i < props.render.components.length; i++) {
            if (props.render.components[i].classType === 'action') {
                actionsArray.push(props.render.components[i]);
            }
        }
    }
    return actionsArray;
});

// Load Languages
const gLang = computed(
    () => usePage().props.value.data.trans
);
const rLang = computed(
    () => props.render.lang
)

let {trans} = useTrans();

//Load Roles
const roles = props.roles;

// Mounted
onMounted(() => {
    //Set Modals
    if(modals.value.length){
        for (let i = 0; i < modals.value.length; i++) {
            actionModal.value[modals.value[i].name] = false;
            modalAction.value[modals.value[i].name] = useForm({});
        }
    }
})


</script>

<template>
    <div class="px-6 pt-6 mx-auto">
        <!-- Main Resource Header -->
        <Header
            v-if="rLang"
            :canCreate="false"
            :title="rLang ? rLang.index : ''"
            :button="rLang ? rLang.create: ''"
            :url="props.list.url+'.index'"
        >
            <!-- Actions Generator -->
            <a
                v-for="(action, index) in actions"
                :key="index"
                :href="action.url ? action.url : '#'"
                @click.prevent="
                            !action.url
                                ? action.modal
                                    ? openModal(action.modal)
                                    : fireAction(action.action)
                                : openUrl(action.url)
                        "
                class="relative inline-flex items-center px-8 py-3 overflow-hidden text-white bg-main rounded group active:bg-blue-500 focus:outline-none focus:ring"
            >
                        <span
                            class="absolute left-0 transition-transform -translate-x-full group-hover:translate-x-4"
                        >
                            <i class="bx-sm mt-2" :class="action.icon"></i>
                        </span>

                <span
                    class="text-sm font-medium transition-all group-hover:ml-4"
                >
                            {{ action.label }}
                        </span>
            </a>
        </Header>

        <!-- Widgets Generator -->
        <div v-if="widgets.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 my-4">
            <div
                v-for="(item, key) in widgets"
                :class="{
                            'col-span-4 lg:col-span-4 md:col-span-4 sm:col-span-4': widgets.length === 1,
                            'col-span-4 lg:col-span-2 md:col-span-2 sm:col-span-2': widgets.length === 2,
                            'bg-success-500': item.type === 'success',
                            'bg-danger-500': item.type === 'danger',
                            'bg-blue-700': item.type === 'primary',
                            'bg-warning-500': item.type === 'warning',
                        }"
                class="w-full bg-blue-500 rounded-lg text-center py-4 px-2 text-white">

                <i class="bx-lg" :class="item.icon"></i>
                <h1 class="text-2xl font-bold">{{ item.label }}</h1>
                <p>{{ item.value }}</p>
            </div>
        </div>
    </div>
    <div class="px-6 mx-auto">
        <div class="bg-white dark:bg-gray-800 dark:border-gray-600  rounded-lg border">
            <Commands />
        </div>
    </div>
    <!-- Modals Generator -->
    <JetDialogModal
        v-for="(item, key) in modals"
        :key="key"
        :show="actionModal[item.name]"
    >
        <template #title>
            <div class="flex justify-between">
                {{ item.label }}
            </div>
        </template>

        <template #content>
            <form action="" enctype="multipart/form-data">
                <ViltForm
                    v-model="modalAction[item.name]"
                    :rows="item.rows"
                    :errors="props.errors"
                />
            </form>
        </template>

        <template #footer>
            <JetButton
                v-for="(button, key) in item.buttons"
                :key="key"
                @click.prevent="modalActionRun(item.name, button.action)"
                class="mx-2"
            >{{ button.label }}
            </JetButton>
            <JetSecondaryButton
                @click="actionModal[item.name] = !actionModal[item.name]"
            >
                {{ trans('global.close') }}
            </JetSecondaryButton>
        </template>
    </JetDialogModal>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@@/Layouts/AppLayout.vue";

export default defineComponent({
    layout: AppLayout,
});
</script>
