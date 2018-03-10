<template>
    <div id="app">
        <div class="main">
            <h1 v-if="!beginLucky">抽奖尚未开始或者已经结束</h1>
        <div v-else>
            <!-- {{name}}--{{phone}} -->
            <div class="card">
                <canvas id="canvas" width="280px" height="100px"></canvas>
                <div class="lucky">{{lucky}}</div>
            </div>
            <div class="msg" v-html="msg"></div>
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
    var width = 280;
    var height = 100;
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
    ctx.strokeText("刮一刮，有惊喜", 140, 50);

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
      if (scrapeNum > myCanvasObject.width * myCanvasObject.height * 0.2) {
        if (vue.lucky != "谢谢惠顾!") {
          vue.msg = `恭喜你中奖了！请及时与我们的工作人员取得联系，电话:<a href='tel:02865006500'>02865006500</a>`;
        } else {
          vue.msg = `很抱歉，您未中奖！`;
        }
      }
      if (scrapeNum > myCanvasObject.width * myCanvasObject.height * 0.5) {
        ctx.clearRect(0, 0, myCanvasObject.width, myCanvasObject.height);
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

export default {
  data() {
    return {
      beginLucky: true,
      phone: "",
      msg: "",
      lucky: null
    };
  },
  mounted() {
    if (this.beginLucky) {
      draw = new Draw();
      draw.init("canvas", this);
      if (window.localStorage.getItem("lucky")) {
        this.lucky = window.localStorage.getItem("lucky");
      } else {
        this.lucky = "谢谢惠顾!";
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
  width: 280px;
  height: 100px;
  margin: 20px auto;
  box-sizing: border-box;
  /* background: #ccc; */
}
h2 {
  text-align: center;
}
.card .lucky {
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 100px;
  z-index: 0;
  font-size: 22px;
  border: 1px solid #ccc;
  color: #fff;
}

#canvas {
  /* background: red; */
  position: absolute;
  z-index: 1;
}
.msg {
  color: #fff;
  text-align: center;
}
</style>
