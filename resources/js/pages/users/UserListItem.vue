<script setup>
import { formatDate } from '../../helper.js';
import axios from "axios";
import {ref} from "vue";
import { useToastr } from '../../toastr.js';

const toastr = useToastr();

defineProps({
    user: Object,
    index: Number,
});

const emit = defineEmits(['userDeleted', 'editUser']);

const deletedUserId = ref(null);


const confirmUserDeletion = (user) => {
    deletedUserId.value = user.id;
    $('#deleteUserModal').modal('show');
};

const deleteUser = () => {
    axios.delete(`/api/users/${deletedUserId.value}`)
        .then(() => {
            $('#deleteUserModal').modal('hide');// hide confirm modal
            toastr.success('User deleted successfully.');// toastr about success
            //users.value = users.value.filter( user => user.id !== deletedUserId.value);//filter remain users
            emit('userDeleted', deletedUserId.value)
        });
};

const roles = ref([
    {
      name: 'ADMIN',
      value: 1,
    },
    {
        name: 'USER',
        value: 2,
    },
]);

const changeRole = (user, role) => {
    //console.log(user, role);
    axios.patch(`/api/users/${user.id}/change-role`, {
        role: role,
    }).then(() => {
        toastr.success('Role changed successfully.')
    })
};

</script>

<template>

    <tr>
        <td><input type="checkbox"></td>
        <td>{{ index+1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <!--<td>{{ user.formatted_created_at}}</td>-->
        <td>{{ formatDate(user.created_at) }}</td>
        <!--<td>{{ user.role_name }}</td>-->
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :selected="(user.role_name === role.name)">{{ role.name }}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser', user)"><i class="fa fa-edit"></i></a>
            <a href="#" @click.prevent="confirmUserDeletion(user)" ><i class="fa fa-trash text-danger ml-2"></i></a>
        </td>
    </tr>


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

