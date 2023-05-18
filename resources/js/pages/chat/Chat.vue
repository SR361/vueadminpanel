<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
</script>
<style scoped>
    .online_user_border{
        border: 1px solid #28a745 !important;
    }
    .offline_user_border{
        border: 1px solid #dc3545 !important;
    }
    .content-header{
        padding : 5px 0.5rem;
    }
    .card-body, .direct-chat-messages
    {
        min-height: 400px;
    }
    .fa-circle{
        font-size: 13px !important;
    }
</style>
<template>
    <div class="wrapper">
        <Header />
        <Sidebar />
        <div class="content-wrapper" >
            <div class="content-header">
                <!-- <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Chat</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Chat</li>
                            </ol>
                        </div>
                    </div>
                </div> -->
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#friend" data-toggle="tab">Friend</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#newuser" data-toggle="tab">New User</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#notification" data-toggle="tab">Notification</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="friend">
                                            <ul class="contacts-list" v-if="connectedUsers.length > 0">
                                                <li v-for="connectedUser in connectedUsers">
                                                    <a href="#" class="online_status_icon"
                                                        @click.prevent="
                                                        make_chat_area(connectedUser.id,connectedUser.name,connectedUser.user_image);
                                                        load_chat_data(this.from_user_id, connectedUser.id);  " >

                                                        <img v-if="connectedUser.user_image" :src="this.baseUrl+connectedUser.user_image" width="40" class="contacts-list-img mr-2">
                                                        <img v-else :src="this.baseUrl+'/default.png'" width="40" class="contacts-list-img mr-2">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                {{ connectedUser.name }}
                                                                <span v-if="connectedUser.user_status === 'Online'" class="text-success online_status_icon" :id="`status_${connectedUser.id}`"><i class="fas fa-circle"></i></span>
                                                                <span v-else class="text-danger online_status_icon" :id="`status_${connectedUser.id}`"><i class="fas fa-circle"></i></span>
                                                                <span class="user_unread_message float-right mt-2" :id="`user_unread_message_${connectedUser.id}`"></span>
                                                            </span>
                                                            <small v-if="connectedUser.user_status === 'online'" class="contacts-list-msg" :id="`last_seen_${connectedUser.id}`">Online</small>
                                                            <small v-else class="contacts-list-msg" :id="`last_seen_${connectedUser.id}`">{{ connectedUser.last_seen }}</small>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul v-else class="contacts-list">
                                                <li>
                                                    <span>User Not Found</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" id="newuser">
                                            <div id="search_people_area">
                                                <ul class="list-group">
                                                    <li class="list-group-item" v-for="unconnectedUser in unconnectedUsers">
                                                        <div class="row">
                                                            <div class="col col-9">
                                                                <img v-if="unconnectedUser.user_image" :src="this.baseUrl+unconnectedUser.user_image" width="40" class="rounded-circle">
                                                                <img v-else :src="this.baseUrl+'/default.png'" width="40" class="rounded-circle">
                                                                {{ unconnectedUser.name }}
                                                            </div>
                                                            <div class="col col-3">
                                                                <button type="button" :disabled="requestSend" name="send_request" class="btn btn-primary btn-sm float-end" @click.prevent="send_request($event, unconnectedUser.id)">
                                                                    <i class="fas fa-paper-plane"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="notification">
                                            <ul v-if="notificationDatas.length > 0" class="list-group" id="notification_area">
                                                <li class="list-group-item" v-for="notificationData in notificationDatas">
                                                    <div class="row">
                                                        <div class="col col-8">
                                                            <img v-if="notificationData.user_image" :src="this.baseUrl+notificationData.user_image" width="40" class="rounded-circle">
                                                            <img v-else :src="this.baseUrl+'/default.png'" width="40" class="rounded-circle">
                                                            {{ notificationData.name }}
                                                        </div>
                                                        <div class="col col-4">
                                                            <div v-if="notificationData.notification_type == 'Send Request'">
                                                                <button v-if="notificationData.status == 'Pending'" type="button" class="btn btn-warning btn-sm float-end">
                                                                    Request Send
                                                                </button>
                                                                <button v-else type="button" class="btn btn-danger btn-sm float-end">
                                                                    Request Rejected
                                                                </button>
                                                            </div>
                                                            <div v-else>
                                                                <div v-if="notificationData.status == 'Pending'">
                                                                    <button type="button" class="btn btn-danger btn-sm float-end"
                                                                        @click.prevent="process_chat_request(notificationData.id, notificationData.from_user_id, notificationData.to_user_id, `Reject`)">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-success btn-sm float-end ml-2"
                                                                        @click.prevent="process_chat_request(notificationData.id,notificationData.from_user_id, notificationData.to_user_id, `Approve`)">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </div>
                                                                <div v-else>
                                                                    <button type="button" name="send_request" class="btn btn-danger btn-sm float-end">Request Rejected</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul v-else class="list-group">
                                                <li class="list-group-item">
                                                    <span>You have no notification</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card direct-chat direct-chat-primary">
                                <div class="card-header pt-2 pb-2">
                                    <div v-if="makeChatArea">
                                        <img v-if="to_user_image" class="direct-chat-img" :src="this.baseUrl+to_user_image" alt="message user image">
                                        <img v-else class="direct-chat-img" :src="this.baseUrl+'/default.png'">
                                        <h3 class="ml-2 card-title" style="line-height:36px;">{{ to_user_name }}</h3>
                                    </div>
                                    <div v-else>
                                        <h3 class="card-title">{{ to_user_name }}</h3>
                                    </div>
                                    <div class="card-tools" v-if="makeChatArea">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        <button type="button" id="close_chat" class="btn btn-tool" @click.prevent="close_chat();"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="card-body pt-1 pb-1" >
                                    <div class="direct-chat-messages" v-if="makeChatArea" id="chat_history">
                                        <div v-for="chatHistoryData in chatHistoryDatas">
                                            <div v-if="chatHistoryData.from_user_id == this.from_user_id" class="direct-chat-msg right">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                    <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                                </div>
                                                <img class="direct-chat-img" :src="this.from_user_image" alt="message user image">
                                                <div class="direct-chat-text">
                                                    {{ chatHistoryData.chat_message }}
                                                </div>
                                            </div>
                                            <div v-else class="direct-chat-msg ">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                    <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                                </div>
                                                <img v-if="to_user_image" class="direct-chat-img" :src="this.baseUrl+this.to_user_image" alt="message user image">
                                                <img v-else class="direct-chat-img" :src="this.baseUrl+'/default.png'" alt="message user image">
                                                <div class="direct-chat-text">
                                                    {{ chatHistoryData.chat_message }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="display: block;" v-if="makeChatArea">
                                    <div class="input-group">
                                        <input id="message_area" type="text" name="message" ref="inputField" placeholder="Type Message ..." class="form-control" @keyup.enter="sendMessage" v-model="chatmessage">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="sendMessage" ><i class="fas fa-paper-plane"></i></button>
                                            <button type="button" class="btn btn-warning" @click="uploadImage">
                                                <i class="fas fa-upload"></i>
                                                <input type="file" id="browse_image" @change="upload_image()" hidden />
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Footer />
    </div>
</template>
<script>
export default {
    data() {
        return {
            socket: {},
            connectedStatus: 'Not connected!',
            message: 'No message yet!',
            from_user_id : '',
            from_user_image : '',
            to_user_id : '',
            to_user_name : 'Chat Area',
            to_user_image : '',
            makeChatArea : '',
            unconnectedUsers : [],
            baseUrl : '',
            requestSend : false,
            notificationDatas : [],
            connectedUsers : [],
            chatmessage : '',
            chatMessageDatas : '',
            chatHistoryDatas : '',
        }
    },
    async mounted() {
        let user = JSON.parse(localStorage.getItem("user"));
        let token = user.token;

        const socketProtocol = (window.location.protocol === 'http:' ? 'ws:' : 'ws:')
        const port = ':8090';
        const echoSocketUrl = socketProtocol + '//' + window.location.hostname + port + '/?token='+token;
        this.baseUrl =  'http://' + window.location.hostname + ':8000' + '/';
        this.from_user_id = user.id;
        this.from_user_image = user.profile_photo;
        this.socket = await new WebSocket(echoSocketUrl);
        this.socket.onopen = () => {
            this.connectedStatus = 'Connected';
            this.load_unconnected_user();
            this.load_unread_notification(this.from_user_id);
            this.load_connected_chat_user(this.from_user_id);
        }
        this.socket.onmessage = (event) => {
            let parsedMessage = JSON.parse(event.data);
            var data = parsedMessage;
            if(data.image_link)
            {
                document.getElementById('message_area').innerHTML = `<img src="`+this.baseUrl+data.image_link+`'" class="img-thumbnail img-fluid" />`;
            }
            if(data.status){
                var online_status_icon = document.getElementsByClassName('online_status_icon');
                for(var count = 0; count < online_status_icon.length; count++){
                    if(online_status_icon[count].id == 'status_'+data.id){
                        if(data.status == 'Online'){
                            online_status_icon[count].classList.add('text-success');
                            online_status_icon[count].classList.remove('text-danger');
                            document.getElementById('last_seen_'+data.id+'').innerHTML = 'Online';
                        }else{
                            online_status_icon[count].classList.add('text-danger');
                            online_status_icon[count].classList.remove('text-success');
                            document.getElementById('last_seen_'+data.id+'').innerHTML = data.last_seen;
                        }
                    }
                }
            }
            if(data.response_load_unconnected_user || data.response_search_user)
            {
                this.unconnectedUsers = data.data;
            }
            if(data.response_load_notification)
            {
                this.notificationDatas = data.data;
            }
            if(data.response_from_user_chat_request)
            {
                // search_user(from_user_id, document.getElementById('search_people').value);
                this.load_unread_notification(this.from_user_id);
            }
            if(data.response_to_user_chat_request)
            {
                this.load_unread_notification(data.user_id);
            }
            if(data.response_process_chat_request)
            {
                this.load_unread_notification(data.user_id);
                this.load_connected_chat_user(data.user_id);
            }
            if(data.response_connected_chat_user){
                this.connectedUsers = data.data;
                this.check_unread_message();
            }
            if(data.message){
                this.chatMessageDatas = data;

                var html = '';
                if(data.from_user_id == this.from_user_id){
                    var icon_style = '';
                    if(data.message_status == 'Not Send'){
                        icon_style = '<span id="chat_status_'+data.chat_message_id+'" class="float-end"><i class="fas fa-check text-muted"></i></span>';
                    }
                    if(data.message_status == 'Send'){
                        icon_style = '<span id="chat_status_'+data.chat_message_id+'" class="float-end"><i class="fas fa-check-double text-muted"></i></span>';
                    }

                    if(data.message_status == 'Read'){
                        icon_style = '<span class="text-primary float-end" id="chat_status_'+data.chat_message_id+'"><i class="fas fa-check-double"></i></span>';
                    }

                    html +=`
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                        </div>
                        <img class="direct-chat-img" src="`+this.from_user_image+`" alt="message user image">
                        <div class="direct-chat-text">
                            `+data.message+`
                        </div>
                    </div>
                    `;
                }else{
                    if(this.to_user_id != ''){
                        if(this.to_user_image){
                            var user_image = this.baseUrl+this.to_user_image;
                        }else{
                            var user_image = this.baseUrl+'default.png';
                        }
                        html +=`
                        <div class="direct-chat-msg ">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">Sarah Bullock</span>
                                <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                            </div>
                            <img class="direct-chat-img" src="`+user_image+`" alt="message user image">
                            <div class="direct-chat-text">
                                `+data.message+`
                            </div>
                        </div>
                        `;
                        this.update_message_status(data.chat_message_id, this.from_user_id, this.to_user_id, 'Read');
                    }else{
                        var count_unread_message_element = document.getElementById('user_unread_message_'+data.from_user_id+'');
                        if(count_unread_message_element){
                            var count_unread_message = count_unread_message_element.textContent;
                            if(count_unread_message == ''){
                                count_unread_message = parseInt(0) + 1;
                            }else{
                                count_unread_message = parseInt(count_unread_message) + 1;
                            }
                            count_unread_message_element.innerHTML = '<span class="badge bg-primary rounded-pill">'+count_unread_message+'</span>';
                            this.update_message_status(data.chat_message_id, data.from_user_id, data.to_user_id, 'Send');
                        }
                    }
                }
                if(html != ''){
                    var previous_chat_element = document.querySelector('#chat_history');
                    var chat_history_element = document.querySelector('#chat_history');
                    chat_history_element.innerHTML = previous_chat_element.innerHTML + html;
                    this.scroll_top();
                }
            }
            if(data.chat_history){
                this.chatHistoryDatas = data.chat_history;
                setTimeout(function(){
                    this.scroll_top();
                }.bind(this), 1000);
                for(var count = 0; count < data.chat_history.length; count++){
                    if(data.chat_history[count].from_user_id == this.from_user_id){

                    }else{
                        if(data.chat_history[count].message_status != 'Read'){
                            this.update_message_status(data.chat_history[count].id, data.chat_history[count].from_user_id, data.chat_history[count].to_user_id, 'Read');
                        }

                        var count_unread_message_element = document.getElementById('user_unread_message_'+data.chat_history[count].from_user_id+'');
                        if(count_unread_message_element){
                            count_unread_message_element.innerHTML = '';
                        }
                    }
                }
            }
            if(data.update_message_status)
            {
                var chat_status_element = document.querySelector('#chat_status_'+data.chat_message_id+'');

                if(chat_status_element)
                {
                    if(data.update_message_status == 'Read')
                    {
                        chat_status_element.innerHTML = '<i class="fas fa-check-double text-primary"></i>';
                    }
                    if(data.update_message_status == 'Send')
                    {
                        chat_status_element.innerHTML = '<i class="fas fa-check-double text-muted"></i>';
                    }
                }

                if(data.unread_msg)
                {
                    var count_unread_message_element = document.getElementById('user_unread_message_'+data.from_user_id+'');

                    if(count_unread_message_element)
                    {
                        var count_unread_message = count_unread_message_element.textContent;

                        if(count_unread_message == '')
                        {
                            count_unread_message = parseInt(0) + 1;
                        }
                        else
                        {
                            count_unread_message = parseInt(count_unread_message) + 1;
                        }

                        count_unread_message_element.innerHTML = '<span class="badge bg-danger rounded-pill">'+count_unread_message+'</span>';
                    }
                }
            }
            if(typeof parsedMessage.message !== "undefined" && parsedMessage.message == "hello") {
                this.message = parsedMessage.message;
            }
        }
    },
    methods: {
        waitForOpenConnection: function() {
            return new Promise((resolve, reject) => {
                const maxNumberOfAttempts = 10
                const intervalTime = 200

                let currentAttempt = 0
                const interval = setInterval(() => {
                    if (currentAttempt > maxNumberOfAttempts - 1) {
                        clearInterval(interval)
                        reject(new Error('Maximum number of attempts exceeded.'));
                    } else if (this.socket.readyState === this.socket.OPEN) {
                        clearInterval(interval)
                        resolve()
                    }
                    currentAttempt++
                }, intervalTime)
            })
        },
        uploadImage: async function(){
            $('#browse_image').trigger('click');
        },
        sendMessage: async function(message) {
            if (this.socket.readyState !== this.socket.OPEN) {
                try {
                    await this.waitForOpenConnection(this.socket)
                    var data = {
                        message : this.chatmessage,
                        from_user_id : this.from_user_id,
                        to_user_id : this.to_user_id,
                        type : 'request_send_message'
                    };
                    this.socket.send(JSON.stringify(data))
                    this.chatmessage = '';
                } catch (err) { console.error(err) }
            } else {
                var data = {
                    message : this.chatmessage,
                    from_user_id : this.from_user_id,
                    to_user_id : this.to_user_id,
                    type : 'request_send_message'
                };
                this.socket.send(JSON.stringify(data))
                this.chatmessage = '';
            }
        },
        load_unconnected_user: async function(){
            let user = JSON.parse(localStorage.getItem("user"));
            var data = {
                from_user_id : this.from_user_id,
                type : 'request_load_unconnected_user'
            };
            this.socket.send(JSON.stringify(data))
        },
        queryForKeywords: async function(event) {
            const value = event.target.value
            this.search_query = value;
            if(this.search_query.length > 0){
                var data = {
                    from_user_id : this.from_user_id,
                    search_query : this.search_query,
                    type : 'request_search_user'
                };

                this.socket.send(JSON.stringify(data));
            }else{
                this.load_unconnected_user();
            }
        },
        send_request: async function(event, to_user_id){
            var data = {
                from_user_id : this.from_user_id,
                to_user_id : to_user_id,
                type : 'request_chat_user'
            };
            this.socket.send(JSON.stringify(data));
        },
        load_unread_notification: async function(user_id){
            var data = {
                user_id : user_id,
                type : 'request_load_unread_notification'
            };
            this.socket.send(JSON.stringify(data));
        },
        process_chat_request: async function(chat_request_id, from_user_id, to_user_id, action){
            var data = {
                chat_request_id : chat_request_id,
                from_user_id : from_user_id,
                to_user_id : to_user_id,
                action : action,
                type : 'request_process_chat_request'
            };
            this.socket.send(JSON.stringify(data));
        },
        load_connected_chat_user: async function(from_user_id)
        {
            var data = {
                from_user_id : from_user_id,
                type : 'request_connected_chat_user'
            };

            this.socket.send(JSON.stringify(data));
        },
        make_chat_area: async function(user_id, to_user_name,user_image){
            this.makeChatArea = true;
            this.to_user_name = to_user_name;
            this.to_user_id = user_id;
            this.to_user_image = user_image;
        },
        load_chat_data: async function(from_user_id, to_user_id){
            var data = {
                from_user_id : from_user_id,
                to_user_id : to_user_id,
                type : 'request_chat_history'
            };

            this.socket.send(JSON.stringify(data));
        },
        update_message_status(chat_message_id, from_user_id, to_user_id, chat_message_status){
            var data = {
                chat_message_id : chat_message_id,
                from_user_id : from_user_id,
                to_user_id : to_user_id,
                chat_message_status : chat_message_status,
                type : 'update_chat_status'
            };

            this.socket.send(JSON.stringify(data));
        },
        check_unread_message: async function(){
            var unread_element = document.getElementsByClassName('user_unread_message');
            for(var count = 0; count < unread_element.length; count++){
                var temp_user_id = unread_element[count].dataset.id;
                var data = {
                    from_user_id : this.from_user_id,
                    to_user_id : this.to_user_id,
                    type : 'check_unread_message'
                };
                this.socket.send(JSON.stringify(data));
            }
        },
        send_chat_message()
        {
            var data = {
                message : this.chatmessage,
                from_user_id : this.from_user_id,
                to_user_id : this.to_user_id,
                type : 'request_send_message'
            };
            this.socket.send(JSON.stringify(data));
            this.chatmessage = '';
        },
        close_chat: async function(){
            this.makeChatArea = false;
            this.to_user_name = 'Chat Area';
            this.to_user_id = '';
        },
        upload_image: async function(){
            var file_element = document.getElementById('browse_image').files[0];

            var file_name = file_element.name;
            var file_extension = file_name.split('.').pop().toLowerCase();
            var allowed_extension = ['png', 'jpg'];
            if(allowed_extension.indexOf(file_extension) == -1){
                alert("Invalid Image File");
                return false;
            }
            var file_reader = new FileReader();
            var file_raw_data = new ArrayBuffer();
            file_reader.loadend = function()
            {

            }
            file_reader.onload = function(event){
                file_raw_data = event.target.result;
                // let filedata = new Uint8Array(file_raw_data).toString()
                // let filedata = event.target.result;
                console.log(file_raw_data);
                // localStorage.setItem("filedata",file_raw_data);
            }
            // let filedatas = localStorage.getItem("filedata");
            // let arrayFileData = new Uint8Array(filedatas.split(',')).buffer
            file_reader.readAsArrayBuffer(file_element);
            console.log(file_reader.result);

        },
        scroll_top: async function(){
            document.querySelector('#chat_history').scrollTop = document.querySelector('#chat_history').scrollHeight;
        }
    }
}
</script>
