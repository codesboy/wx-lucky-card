<template>
    <div id="app" class="main">
        <div v-if="!islucky">
            <h2 v-if="!beginLucky" class="nobegin">活动尚未开始或已经结束（刮奖将在2018-03-13~2018-03-30下午17:30到18:00开启（除周六））</h2>
            <div class="content" v-else>
                <h2>贝臣口腔特价做牙幸运刮奖</h2>
                <div class="item">
                    <label>手机号</label>
                    <p><input placeholder="请输入手机号" v-model="phone" /></p>
                </div>
                <div class="btn" @click="isLogin">立即参与</div>
                <div class="notice">为了保证您顺利进行刮奖，请务必正确填写您的手机号,该手机号将作为您的兑奖凭证!</div>
                <div>{{errmsg}}</div>
            </div>
        </div>
        <div v-else class="result">
            <p class="result-tit">尊敬的{{phone}}客户，您本次参与刮奖的结果如下：</p>
            <div class="card">
                <div class="lucky">
                    {{lucky}}

                    <div v-html="description" class="howuse"></div>
                </div>
            </div>
            <p class="sn">本券最终解释权归贝臣口腔所有</p>
        </div>

    </div>
</template>
	<script>
import axios from "axios";
export default {
    data() {
        return {
            beginLucky: false,
            phone: "",
            errmsg: "",
            islucky: false, //是否已经参与过抽奖
            lucky: "",
            description: ""
        };
    },
    created() {
        // window.localStorage.clear();
        if (window.localStorage.getItem("lucky_status") == 2) {
            this.islucky = true;
            this.lucky = window.localStorage.getItem("lucky");
            this.phone = window.localStorage.getItem("phone");
            this.description = window.localStorage.getItem("description");
        }
        axios.get("https://bcwx.rehack.cn/api/v1/getisstart").then(response => {
            if (response) {
                console.log(response.data);
                this.beginLucky = response.data;
            }
        });
    },
    methods: {
        isLogin: function() {
            if (window.localStorage.getItem("lucky_status") == "2") {
                //   this.$router.push({ path: "main" });
                // alert('您已经参与过本次刮刮奖活动了！详情请咨询：028-65006500');
                this.beginLucky = false;
                return false;
            }
            axios
                .post("https://bcwx.rehack.cn/api/v1/signup", {
                    phone: this.phone
                })
                .then(response => {
                    if (response.data.phone == this.phone) {
                        if (response.data.lucky_status == 2) {
                            // window.localStorage.setItem("lucky_status", 2)
                            alert(
                                "您已经参与过本次刮刮奖活动了！详情请咨询：028-65006500"
                            );
                            return false;
                        }
                        this.$router.push({ path: "main" });
                        window.localStorage.setItem("phone", this.phone); //本地存储标记已经报名
                        window.localStorage.setItem(
                            "lucky",
                            response.data.lucky_item
                        ); //本地存储标记已经报名
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
<style scoped>
p {
    margin: 0;
    padding: 0;
}
.main {
    width: 100%;
    min-width: 320px;
    max-width: 640px;
    margin: 0 auto;
    background: url("../assets/bg1.jpg") no-repeat 0 0;
    background-size: cover;
    height: 100vh;
    /* padding: 0 20px; */
    box-sizing: border-box;
    color: #fff;
    /* text-align: center; */
}
.main .content {
    width: 80%;
    margin: 0 auto;
    padding-top: 130px;
    display: flex;
    flex-direction: column;
}
.main .content h2 {
    text-align: center;
    color: #fff;
}
.nobegin {
    padding-top: 30vh;
    text-align: center;
    margin: 0;
}
.item {
    margin: 10px 0;
}
input {
    width: 100%;
    height: 50px;
    padding-left: 6px;
    outline: none;
    border: 1px solid #ccc;
    border-radius: 6px;
    -webkit-appearance: none;
    box-sizing: border-box;
}
.btn {
    width: 100%;
    height: 35px;
    margin: 0 auto;
    line-height: 35px;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    background: rgb(233, 104, 107);
    margin: 10px 0;
}
.notice {
    font-size: 14px;
    color: red;
}
.result {
    color: red;
}
.result-tit {
    padding-top: 150px;
    text-align: center;
}

.card {
    position: relative;
    width: 300px;
    margin: 10px auto;
    box-sizing: border-box;
    color: red;
    border: 1px solid red;
    border-radius: 4px;
}
h2 {
    text-align: center;
}
.card .lucky {
    width: 100%;
    height: 100%;
    text-align: center;
    z-index: 0;
    font-size: 22px;
}
.howuse>>>ul {
    font-size: 14px;
    text-align: left;
    margin: 0;
    padding: 0;
    width: 80%;
    margin: 0 auto;
}
.howuse>>>span {
    display: block;
    font-size: 16px;
    text-align: left;
    margin-left: 5px;
}
.howuse>>>img {
    width: 50%;
}
.sn {
    font-size: 12px;
    text-align: center;
}
</style>
