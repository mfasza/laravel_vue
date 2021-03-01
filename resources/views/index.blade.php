<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Vue - Ajax</title>
</head>
<body>

    <div id="app">
        <div>
            <input type="text" v-model="input_text">
            <button id="btn" v-show="!editing" v-on:click="add">Add</button>
            <button id="btn_edit" v-show="editing" v-on:click="update">Update</button>
        </div>

        <ul>
            <li v-for="(user, index) in users" style="padding-bottom: 20px;">
                @{{ user.name }}&nbsp;
                <button v-on:click="edit(user)">Edit</button> || <button v-on:click="del(index, user)">Delete</button>
            </li>
        </ul>

    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
    <script>
        let app = new Vue({
            el: "#app",
            data: {
                users: [],
                editing: false,
                input_text: null,
                selectedUser: {}
            },
            methods: {
                add: function(){
                    if (this.input_text.trim()) {
                        this.$http.post('/api/user/store/', {name: this.input_text}).then(response => {
                            this.users.push({id: response.body.data.id, name:this.input_text});
                            this.input_text = null;
                        });
                    }
                },
                edit: function(user){
                    this.input_text = user.name;
                    this.selectedUser = user;
                    this.editing = true;
                },
                update: function(){
                    if (this.input_text.trim()) {
                        this.$http.post('/api/user/update/'+this.selectedUser.id, {name:this.input_text}).then(response => {
                            this.selectedUser.name = this.input_text;
                            this.editing = false;
                            this.selectedUser = {}
                            this.input_text = null;
                        });
                    }
                },
                del: function(index, user){
                    if (confirm("Apakah Anda yakin ingin menghapus?")) {
                        this.$http.post('/api/user/destroy/'+user.id).then(response => {
                            this.users.splice(index,1);
                        });
                    }
                }
            },
            mounted: function(){
                this.$http.get('/api/user/list').then(response => {
                    let result = response.body.data;
                    this.users = result

                });
            }
        });
    </script>
</body>
</html>