<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':session_block}">
                {{ friend.name }} 
                <span v-if="session_block">(Blocked)</span>
            </b>

            <a href="" @click.prevent="close">
                <i class="fa fa-times float-right" aria-hidden="true"></i>
            </a>

            <div class="dropdown float-right mr-4">
                <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" v-if="session_block" @click="unblock">Unblock</a>
                    <a class="dropdown-item" href="#" v-else @click="block">Block</a>
                    <a class="dropdown-item" href="#" @click="clear">Clear Chat</a>
                </div>
            </div>
        </div>

        <div class="card-body" v-chat-scroll>
            <p class="card-text" v-for="chat in chats" :key="chat.message">
                {{ chat.message }}
            </p>
        </div>

        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Write your message here" :disabled="session_block" v-model="message">
            </div>
        </form>
    </div>
</template>

<script>
export default {
  props: ["friend"],
  data() {
    return {
      chats: [],
      message: null,
      session_block: false
    };
  },
  methods: {
    send() {
      if(this.message) {
        this.chats.push(this.message);
        axios.post(`/send/${this.friend.session.id}`, {
          content : this.message,
          to_user : this.friend.id
        })
        .then(response => {console.log(response)});
        this.message = null;
      }
    },
    close() {
      this.$emit("close");
    },
    clear() {
      this.chats = [];
    },
    block() {
      this.session_block = true;
    },
    unblock() {
      this.session_block = false;
    }
  },
  created() {
    this.chats.push(
      { message: "Heyy" },
      { message: "How are you" },
      { message: "How are you1" },
      { message: "How are you2" },
      { message: "How are you3" },
      { message: "How are you4" },
      { message: "How are you5" },
      { message: "How are you6" },
      { message: "How are you7" },
      { message: "How are you8" },
      { message: "How are you9" },
      { message: "How are you10" },
      { message: "How are you11" },
      { message: "How are you12" },
      { message: "How are you13" },
      { message: "How are you14" }
    );
  }
};
</script>

<style>
.chat-box {
  height: 400px;
}
.card-body {
  overflow-y: scroll;
}
</style>
