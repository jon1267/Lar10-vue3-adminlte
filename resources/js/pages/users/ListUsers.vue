<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { Field, Form } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../toastr.js';

const toastr = useToastr();
const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const deletedUserId = ref(null);

const getUsers = () => {
    axios.get('/api/users')
        .then((response) => {
            users.value = response.data; //console.log(response.data)
        })
}

const createUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});

const editUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    //password: yup.string().when((password, schema) => {
    //    return password ? schema.required().min(8) : schema;
    //}),
});

const createUser = (values, {resetForm, setErrors }) => {
    //console.log(values);
    axios.post('/api/users', values)
        .then((response) => {
            //console.log(response.data);
            users.value.unshift(response.data);//users.value.push(response.data);
            $('#userFormModal').modal('hide');
            resetForm();
            toastr.success('User created successfully.');
        })
        .catch((error) => {
            //console.log(error);//this {clg} help find array with errors :(
            if (error.response.data.errors) {
                setErrors(error.response.data.errors)
            }
        })
}

const addUser = () => {
    editing.value = false;
    $('#userFormModal').modal('show');
}

const editUser = (user) => {
    //console.log(user);
    editing.value = true;
    form.value.resetForm()
    $('#userFormModal').modal('show');
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };
}

const updateUser = (values, {setErrors}) => {
    //console.log(values)
    axios.put('/api/users/'+formValues.value.id, values)
        .then((response) => {
            const index = users.value.findIndex(user => user.id === response.data.id)
            users.value[index] = response.data;
            $('#userFormModal').modal('hide');
            toastr.success('User updated successfully.');
        })
        .catch((error) => {
            console.log(error)
            setErrors(error.response.data.errors);
        })
        //.finally(() => {
        //    form.value.resetForm();
        //});
}

const handleSubmit = (values, actions) => {
    console.log(actions);
    editing.value ?
        updateUser(values, actions) :
        createUser(values, actions);
}

//const handleSchema = () => {
//    return editing.value ? editUserSchema : createUserSchema;
//}

const confirmUserDeletion = (user) => {
    deletedUserId.value = user.id;
    $('#deleteUserModal').modal('show');
};

const deleteUser = () => {
    axios.delete(`/api/users/${deletedUserId.value}`)
        .then(() => {
            $('#deleteUserModal').modal('hide');// hide confirm modal
            users.value = users.value.filter( user => user.id !== deletedUserId.value);//filter remain users
            toastr.success('User deleted successfully.');// toastr about success
        });
};

onMounted(() => {
    getUsers();
});

</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <!-- data-toggle="modal" data-target="#userFormModal" -->
            <button @click="addUser" type="button" class="mb-2 btn btn-primary" >
                Add New User
            </button>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index+1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    <a href="#" @click.prevent="editUser(user)"><i class="fa fa-edit"></i></a>
                                    <a href="#" @click.prevent="confirmUserDeletion(user)" ><i class="fa fa-trash text-danger ml-2"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control " :class="{'is-invalid': errors.name}" id="name"
                                   aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control " :class="{'is-invalid': errors.email}" id="email"
                                   aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" type="password" class="form-control " :class="{'is-invalid': errors.password}" id="password"
                                   aria-describedby="nameHelp" placeholder="Enter password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabelDelete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabelDelete">
                        <span >Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5>Are your sure you want delete this user ?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                </div>

            </div>
        </div>
    </div>

</template>

