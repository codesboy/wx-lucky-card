<template>
	<div id="app">
        <h2>报名刮奖</h2>
        <div>
            <label>姓名</label>
            <p><input placeholder="请输入姓名" v-model="name"/></p>
        </div>
        <div>
            <label>手机号</label>
            <p><input placeholder="请输入手机号" v-model="phone"/></p>
        </div>
        <button @click="isLogin">立即报名</button> 
        <div>{{errmsg}}</div>
    </div>
	</template>
	<script>
        import axios from "axios";
		export default {
			data() {
				return {
					name:'',
                    phone:'',
                    errmsg:'',
                    issignup:1
				}
			},
			created() {
				if (window.localStorage.getItem("issignup") == "2") {
                    this.$router.push({ path: 'main' });
                    return false;
                }
			},
			methods:{
				isLogin:function() {
                    axios
                    .post("https://bcwx.rehack.cn/api/v1/signup", {
                        'name':this.name,
                        'phone':this.phone
                    })
                    .then(response => {
                        if (response.data.phone==this.phone) {
                            alert('报名成功')
                            this.$router.push({ path: 'main' });
                            window.localStorage.setItem("name", this.name);//本地存储标记已经报名
                            window.localStorage.setItem("phone", this.phone);//本地存储标记已经报名
                        }else{
                            alert('未知错误')
                        }
                    })
                    .catch(err=>{
                        alert('发生错误')
                        this.errmsg=err.response.data
                    });
					
				}
			}
		}
	</script>
	<style>
		
	</style>