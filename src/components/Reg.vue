<template>
	<div id="app" class="main">
        <h2>报名刮奖</h2>
        <div class="item">
            <label>手机号</label>
            <p><input placeholder="请输入手机号" v-model="phone"/></p>
        </div>
        <div class="btn" @click="isLogin">立即参与</div> 
        <div class="notice">为了保证您顺利进行刮奖，请务必正确填写您的资料,该资料将作为您的兑奖凭证!</div>
        <div>{{errmsg}}</div>
    </div>
	</template>
	<script>
import axios from "axios";
export default {
  data() {
    return {
      name: "",
      phone: "",
      errmsg: "",
      issignup: 1
    };
  },
  created() {
    if (window.localStorage.getItem("issignup") == "2") {
      this.$router.push({ path: "main" });
      return false;
    }
  },
  methods: {
    isLogin: function() {
      axios
        .post("https://bcwx.rehack.cn/api/v1/signup", {
          phone: this.phone
        })
        .then(response => {
          if (response.data.phone == this.phone) {
            
            this.$router.push({ path: "main" });
            window.localStorage.setItem("phone", this.phone); //本地存储标记已经报名
            window.localStorage.setItem("lucky", response.data.lucky_item); //本地存储标记已经报名
          } else {
            alert("未知错误");
          }
        })
        .catch(err => {
          alert("抱歉，您没有参与本次刮奖的权限，请咨询65006500");
        //   this.errmsg = err.response.data;
        //   if(err.response.data.msg.phone=='phone已存在'){
        //       window.localStorage.setItem("name", this.name); //本地存储标记已经报名
        //     window.localStorage.setItem("phone", this.phone); //本地存储标记已经报名
        //       this.$router.push({ path: "main" });
        //   }
        });
    }
  }
};
</script>
<style>
@import url('../assets/base.css');
.main {
    width: 80%;
  margin: 0 auto;
}
.item{
    margin: 10px 0;
}
input{
    height: 30px;
    padding-left: 6px;
    outline: none;
    border:1px solid #ccc;
    border-radius: 6px;
    -webkit-appearance: none;
}
.btn{
    width: 100px;
    height: 35px;
    line-height: 35px;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    background: rgb(233, 104, 107);
}
.notice{
    font-size: 12px;
    color: red;
}
</style>
