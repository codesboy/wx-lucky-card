<template>
    <div id="app">
        <div class="main">
            <h2 v-if="!beginLucky">您已经参与过本次刮刮奖活动了！详情请咨询：028-65006500</h2>
            <div v-else>
                <!-- {{name}}--{{phone}} -->
                <div class="card" v-bind:class="nolucky?'no-lucky':''">
                    <canvas id="canvas" width="300px" height="180px"></canvas>
                    <div class="lucky">
                        {{lucky}}
                        
                        <div v-html="description" class="howuse"></div>
                    </div>
                </div>
                <div class="msg" v-bind:class="nolucky?'no-lucky':''" v-html="msg"></div>
            </div>
        </div>
        
    </div>
</template>

<script>
import axios from "axios";
var draw;
var preHandler = function(e) {
  e.preventDefault();
};
import cardImg from "../assets/card.jpg";
class Draw {
  init(id, vue) {
    var width = 300;
    var height = 180;
    var myCanvasObject = document.getElementById(id);
    var ctx = myCanvasObject.getContext("2d");

    // 绘制黑色矩形
    ctx.beginPath();
    ctx.fillStyle = "#939393";
    ctx.rect(2, 2, width - 2, height - 2);
    ctx.closePath();
    ctx.fill();

    ctx.font = "20px sans-serif";
    ctx.strokeStyle = "#000";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.strokeText("刮一刮，有惊喜", 150, 90);

    // var img = new Image();
    // img.onload = function() {
    //   ctx.drawImage(img, 0, 0);
    // };
    // // img.src = "https://bcwx.rehack.cn/static/lucky/images/card.jpg";
    // img.src = cardImg;

    var isDown = false; //鼠标是否按下标志
    var pointerArr = []; //鼠标移动坐标数组
    var xPointer = 0; //鼠标当前x坐标
    var yPointer = 0; //鼠标当前y坐标

    //pc，移动事件兼容写法
    var hastouch = "ontouchstart" in window ? true : false,
      tapstart = hastouch ? "touchstart" : "mousedown",
      tapmove = hastouch ? "touchmove" : "mousemove",
      tapend = hastouch ? "touchend" : "mouseup";

    //鼠标按下
    myCanvasObject.addEventListener(tapstart, function(event) {
      //   console.log("按下");
      var e = window.event || event; //2017-12-06
      this.style.cursor = "move";
      isDown = true;
      xPointer = hastouch
        ? e.targetTouches[0].pageX - myCanvasObject.getBoundingClientRect().left
        : e.clientX - this.offsetLeft;
      yPointer = hastouch
        ? e.targetTouches[0].pageY - myCanvasObject.getBoundingClientRect().top
        : e.clientY - this.offsetTop;

      console.log(xPointer, yPointer);
      pointerArr.push([xPointer, yPointer]);
      circleReset(ctx);
    });

    //鼠标按下后拖动
    myCanvasObject.addEventListener(tapmove, function(event) {
      // console.log("拖动");
      var e = window.event || event; //2017-12-06
      if (isDown) {
        xPointer = hastouch
          ? e.targetTouches[0].pageX -
            myCanvasObject.getBoundingClientRect().left
          : e.clientX - this.offsetLeft;
        yPointer = hastouch
          ? e.targetTouches[0].pageY -
            myCanvasObject.getBoundingClientRect().top
          : e.clientY - this.offsetTop;
        pointerArr.push([xPointer, yPointer]);
        circleReset(ctx);
      }
    });

    //鼠标抬起取消事件
    myCanvasObject.addEventListener(tapend, function(event) {
      isDown = false;
      pointerArr = [];
      var data = ctx.getImageData(
          0,
          0,
          myCanvasObject.width,
          myCanvasObject.height
        ).data,
        scrapeNum = 0;
      //   console.log(a);
      for (var i = 3, len = data.length; i < len; i += 4) {
        if (data[i] === 0) {
          scrapeNum++;
        }
      }
      if (scrapeNum > myCanvasObject.width * myCanvasObject.height * 0.1) {
        if (vue.lucky != "谢谢参与") {
          vue.msg = `恭喜你中奖了！我们的工作人员稍后将会与您取得联系！`;
        } else {
          vue.msg = `很抱歉，您未中奖！`;
        }
      }
      if (scrapeNum > myCanvasObject.width * myCanvasObject.height * 0.1) {
        ctx.clearRect(0, 0, myCanvasObject.width, myCanvasObject.height);
        vue.nolucky=true;
      }
    });

    //圆形橡皮檫
    var circleReset = function(ctx) {
      // console.log('檫')
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(pointerArr[0][0], pointerArr[0][1]);
      ctx.lineCap = "round"; //设置线条两端为圆弧
      ctx.lineJoin = "round"; //设置线条转折为圆弧
      ctx.lineWidth = 20; //画笔大小
      ctx.globalCompositeOperation = "destination-out";
      if (pointerArr.length == 1) {
        ctx.lineTo(pointerArr[0][0] + 1, pointerArr[0][1] + 1);
      } else {
        for (var i = 1; i < pointerArr.length; i++) {
          ctx.lineTo(pointerArr[i][0], pointerArr[i][1]);
          ctx.moveTo(pointerArr[i][0], pointerArr[i][1]);
        }
      }
      ctx.closePath();
      ctx.stroke();
      ctx.restore();
    };
  }
}

let desc=[];
export default {
  data() {
    return {
      beginLucky: true,
      phone: "",
      msg: "",
      lucky: null,
      description:'',
      nolucky:false
    };
  },
  mounted() {
    if (this.beginLucky) {
      draw = new Draw();
      draw.init("canvas", this);
      let lucky_item = window.localStorage.getItem("lucky");
      if (lucky_item) {
          this.lucky = lucky_item;
        switch (lucky_item) {
            case '8.8元德国ICX种植体':
                this.description=`
                    <span>使用须知</span>
                    <ul><li>凭您的手机号可享德国ICX种植体特价8.8元1颗,含种植体、基台;</li>
                    <li>种植2颗及2颗以上才能使用;</li>
                    <li>每个人只能参与一次刮奖，不得累计使用;不与其他优惠同享;</li>
                    <li>不找零、不兑换现金、不得转让。</li>
                    <li>有效截止日期：2018年03月31日</li></ul>
                `;
                break;
            case '8.8元二氧化锆全瓷牙1颗':
                this.description=`
                    <span>使用须知</span>
                    <ul><li>凭您的手机号可享二氧化锆全瓷牙特价8.8元1颗;</li>
                    <li>仅限做种植牙可用;</li>
                    <li>每个人只能参与一次刮奖，不得累计使用;不与其他优惠同享;</li>
                    <li>不找零、不兑换现金、不得转让。</li>
                    <li>有效截止日期：2018年03月31日</li></ul>
                `;
                break;
            case '8.8元特价种植体':
                this.description=`
                    <span>使用须知</span>
                    <ul><li>凭您的手机号可享韩国登腾种植体特价8.8元1颗，含进口种植体、基台;</li>
                    <li>每个人只能参与一次刮奖，不得累计使用;</li>
                    <li>不得与门店其他优惠同时使用;</li>
                    <li>不找零、不兑换现金、不得转让。</li>
                    <li>有效截止日期：2018年03月31日</li></ul>
                `;
                break;
            case '8.8元韩国登腾':
                this.description=`
                    <span>使用须知</span>
                    <ul><li>凭您的手机号可享韩国登腾种植体特价8.8元1颗，含种植体、基台;</li>
                    <li>种植2颗及2颗以上才能使用;</li>
                    <li>每个人只能参与一次刮奖，不得累计使用;不与其他优惠同享;</li>
                    <li>不找零、不兑换现金、不得转让。</li>
                    <li>有效截止日期：2018年03月31日</li></ul>
                `;
                break;
            default:
                this.description='';
        }
      } else {
        this.lucky = "谢谢参与";
      }
    }
  },
  created() {
    let st = window.localStorage;
    // st.clear();
    if (st.getItem("lucky_status") == 2) {
      this.beginLucky = false;
    }
    if (st.getItem("phone")) {
      this.phone = st.getItem("phone");
    } else {
      this.$router.push({ path: "/" });
    }
  },
  watch: {
    msg(curVal, oldVal) {
      if (curVal != oldVal) {
        console.log(curVal);
        axios
          .post("https://bcwx.rehack.cn/api/v1/changeluckystatus", {
            phone: this.phone
          })
          .then(function(response) {
            console.log(response);
            window.localStorage.setItem("lucky_status", 2);
          })
          .catch(function(response) {
            console.log(response);
          });
      }
    }
  }
};
</script>

<style scoped>
@import "../assets/base.css";

#app {
  background: url("../assets/bg2.jpg") no-repeat 0 0;
  background-size: cover;
  height: 100vh;
  width: 100%;
  margin: 0;
  padding: 0;
}
.main {
  width: 80%;
  margin: 0 auto;
  padding-top: 40vh;
}
.card {
  position: relative;
  width: 300px;
  height: 180px;
  margin: 10px auto;
  /* padding: 8px 0; */
  box-sizing: border-box;
  /* background: #ccc; */
}
.no-lucky{
    height: auto;
    color: #fff!important;
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
  border: 1px solid #ccc;
  color: #fff;
}
.howuse >>> ul{
    font-size: 12px;
    text-align: left;
    margin: 0;
    padding: 0;
    width: 80%;
    margin: 0 auto;
    color: #e8e414;
}
.howuse >>> ul li{
    /* margin: 3px 0; */
}
.howuse >>> span{
    display: block;
    font-size: 15px;
    text-align: left;
    margin-left: 5px;
}
#canvas {
  /* background: red; */
  position: absolute;
  z-index: 1;
}
.msg {
  color: red;
  text-align: center;
}
</style>
