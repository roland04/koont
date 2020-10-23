<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar categorías
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow sm:rounded-md px-4 py-4">
                    <button @click="openModal()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        <i class="fas fa-plus mr-2"></i>
                        Crear nueva categoría
                        </button>
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                                        <thead>
                                            <tr class="border-b-2 border-gray-400">
                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="row in data" v-bind:key="row.id">
                                                <td class="px-6 py-4 whitespace-no-wrap">{{ row.name }}</td>
                                                <td class="px-6 py-4">{{ row.description }}</td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-right">
                                                    <button @click="edit(row)" class="text-sm text-gray-500 hover:text-gray-600"><i class="fas fa-pencil-alt"></i></button>
                                                    <button @click="deleteRow(row)" class="ml-1 text-sm text-red-500 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save and Edit Modal -->
                    <jet-dialog-modal :show="isOpen" @close="closeModal">
                        <template #title>
                            <span  v-show="!editMode">Nueva categoría</span>
                            <span  v-show="editMode">Editar categoría</span>
                        </template>

                        <template #content>
                            <div class="mt-4">
                                <jet-label for="categoryname" value="Nombre" />
                                <jet-input type="categoryname" class="mt-1 block w-full" placeholder="Nombre de categoría"
                                            ref="categoryname"
                                            v-model="form.name"
                                            @keyup.enter.native="handleSubmitModal" tabindex="0" autofocus/>
                                <div v-if="$page.errors.name" class="text-sm text-red-600">{{ $page.errors.name[0] }}</div>
                                <jet-label for="categorydescription" value="Descripción" class="mt-3"/>
                                <jet-input type="categorydescription" class="mt-1 block w-full" placeholder="Descripción de categoría"
                                            ref="categorydescription"
                                            v-model="form.description"
                                            @keyup.enter.native="handleSubmitModal" />
                                <div v-if="$page.errors.description" class="text-sm text-red-600">{{ $page.errors.description[0] }}</div>
                            </div>
                        </template>
                        <template #footer>
                            <jet-secondary-button @click.native="closeModal">
                                Cancelar
                            </jet-secondary-button>
                            <jet-button class="ml-2" @click.native="handleSubmitModal" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <span  v-show="!editMode">Guardar</span>
                                <span  v-show="editMode">Actualizar</span>
                            </jet-button>
                        </template>
                    </jet-dialog-modal>

                    <!-- Delete Confirmation Modal -->
                    <jet-confirmation-modal :show="isDeleting" @close="() => {isDeleting = false; reset()}">
                        <template #title>
                            Borrar categoría
                        </template>
                        <template #content>
                            ¿Estas seguro de querer borrar la categoría "{{form.name}}"?
                        </template>
                        <template #footer>
                            <jet-secondary-button @click.native="() => {isDeleting = false; reset()}">
                                Cancelar
                            </jet-secondary-button>
                            <jet-danger-button class="ml-2" @click.native="deleteCategory" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Borrar
                            </jet-danger-button>
                        </template>
                    </jet-confirmation-modal>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from './../Layouts/AppLayout'
    import JetDialogModal from './../Jetstream/DialogModal'
    import JetButton from './../Jetstream/Button'
    import JetSecondaryButton from './../Jetstream/SecondaryButton'
    import JetInput from './../Jetstream/Input'
    import JetInputError from './../Jetstream/InputError'
    import JetLabel from './../Jetstream/Label'
    import JetConfirmationModal from './../Jetstream/ConfirmationModal'
    import JetDangerButton from './../Jetstream/DangerButton'

    export default {
        components: {
            AppLayout,
            JetDialogModal,
            JetButton,
            JetSecondaryButton,
            JetInput,
            JetInputError,
            JetLabel,
            JetConfirmationModal,
            JetDangerButton,
        },
        props: ['data', 'errors'],
        data() {
            return {
                editMode: false,
                isOpen: false,
                isDeleting: false,
                form: {
                    name: '',
                    description: '',
                },
            }
        },
        methods: {
            openModal: function () {
                this.isOpen = true;
                setTimeout(() => {
                    this.$refs.categoryname.focus();
                }, 250)
            },
            closeModal: function () {
                this.editMode=false;
                this.isOpen = false;
                this.reset();
            },
            handleSubmitModal: function () {
                if (!this.editMode) {
                    this.save(this.form);
                } else {
                    this.update(this.form);
                }
            },
            reset: function () {
                this.form = {
                    name: '',
                    description: '',
                }
                this.$page.errors = [];
            },
            save: async function (data) {
                await this.$inertia.post('/categories', data)
                if (Object.keys(this.$page.errors).length === 0) {
                    this.editMode = false;
                    this.closeModal();
                }
            },
            edit: function (data) {
                this.form = Object.assign({}, data);
                this.editMode = true;
                this.openModal();
            },
            update: async function (data) {
                data._method = 'PUT';
                await this.$inertia.post('/categories/' + data.id, data);
                if (Object.keys(this.$page.errors).length === 0) {
                    this.closeModal();
                }
            },
            deleteRow: function (data) {
                this.isDeleting = true;
                this.form = Object.assign({}, data);
            },
            deleteCategory: async function () {
                let data = this.form;
                data._method = 'DELETE';
                await this.$inertia.post('/categories/' + data.id, data)
                this.reset();
                this.isDeleting = false;
            }
        }
    }
</script>