<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">Friend</div>

                    <ul class="list-group">
                        <li class="list-group-item" @click.prevent="openChat(friend)" :key=friend.id v-for="friend in friends"> 
                            <a href="">
                                {{ friend.name }}
                            </a>
                            <i class="fa fa-circle float-right text-success" v-if="friend.online" aria-hidden="true"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <span v-for="friend in friends" :key="friend.id" v-if="friend.session">
                    <message v-if="friend.session.open" @close="close(friend)" :friend=friend></message>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import Message from "./Message.vue";

export default {
  components: { Message },
  data() {
    return {
      friends: []
    };
  },
  methods: {
    close(friend) {
      friend.session.open = false;
    },
    getFriends() {
      axios.post("/getFriends").then(response => {
        this.friends = response.data.data;
      });
    },
    openChat(friend) {
      if (friend.session) {
        this.friends.forEach(
          friend => (friend.session ? (friend.session.open = false) : "")
        );
        friend.session.open = true;
      } else {
        this.createSession(friend);
      }
    },
    createSession(friend) {
      axios.post("/session/create", { friend_id: friend.id }).then(response => {
        friend.session = response.data.data;
        friend.session.open = true;
      });
    }
  },
  created() {
    this.getFriends();

    Echo.channel('Chat').listen('SessionEvent', e => {
      console.log(e);
    })

    Echo.join("Chat")
      .here(users => {
        this.friends.forEach(friend => {
          users.forEach(user => {
            if (user.id == friend.id) {
              friend.online = true;
            }
          });
        });
      })
      .joining(user => {
        this.friends.forEach(friend => {
          user.id == friend.id ? friend.online=true : '';
        });
      })
      .leaving(user => {
        this.friends.forEach(friend => {
          user.id == friend.id ? friend.online=false : '';
        });
      });
  },
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
