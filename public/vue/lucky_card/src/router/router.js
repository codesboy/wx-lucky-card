import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        name: 'login',
        component: (resolve) => { require(['../components/Login'], resolve) }
    },
    {
        path: '/main',
        name: 'main',
        component: (resolve) => { require(['../components/Home'], resolve) },
        redirect: 'main/info',
        children: [{
            path: 'info',
            meta: {
                id: -1
            },
            component: (resolve) => { require(['../components/Main'], resolve) }
        }
        ]
    },
    {
        path: '/vips',
        name: 'vips',
        component: (resolve) => { require(['../components/Home'], resolve) },
        redirect: 'vips/list',
        children: [{
            path: 'list',
            meta: {
                id: 0
            },
            component: (resolve) => { require(['../components/VipsList'], resolve) }
        },
        {
            path: 'detail',
            meta: {
                id: 0
            },
            component: (resolve) => { require(['../components/VipsDetail'], resolve) }
        },
        {
            path: 'userlog',
            meta: {
                id: 0
            },
            component: (resolve) => { require(['../components/UserLog'], resolve) }
        }
        ]
    }
];

const router = new Router({
    routes
});

/** 
 * to:表示目标路由 
 * from:表示来源路由 
 * next:表示执行下一步操作 
 */
router.beforeEach((to, from, next) => {
    if (to.path === '/login') { // 当路由为login时就直接下一步操作  
        next();
    } else { // 否则就需要判断  
        if (sessionStorage.username) {  // 如果有用户名就进行下一步操作  
            next()
        } else {
            next({ path: '/login' })  // 没有用户名就跳转到login页面  
        }
    }
})

export default router  