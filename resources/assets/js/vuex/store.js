import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getters'
import actions from './actions'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        auth: {
            id: '',
            first_name: '',
            last_name: '',
            email: '',
            avatar: '',
            roles: [],
        },
        is_auth: false,
        two_factor_code: null,
        config: {
            color_theme:"purple",
            locale:"en",
            timezone:"Asia/Ho_Chi_Minh",
            notification_position:"toast-bottom-right",
            page_length:25,
            company_name:"Jtec HN",
            company_description:"Hệ thống quản lý",
            contact_info:"",
            reset_password_token_lifetime:30,
            lock_screen:null,
            lock_screen_timeout:60,
            activity_log:1,
            reset_password:1,
            registration:1,
            footer_credit:"Copyright © 2024 Jtechn",
            multilingual:1,
            post:1,
            facebook_group:"",
            default_cover:"uploads/images/cover-default.png",
            maintenance_mode_message:"Briefly unavailable for scheduled maintenance. Check back in a minute.",
            app_url:"http://localhost",
            public_login:1,
            https:null,
            maintenance_mode:null,
            config_type:"system",
            post_max_size:8388608,
            pagination:["5","10","25","50"]
        },
        permissions: [],
        last_activity: null,
        default_role: {
            admin: '',
            user: ''
        },
        search_query: '',
        search_category_id: ''
    },
    mutations: {
        setAuthStatus(state) {
            state.is_auth = true;
        },
        setAuthUserDetail(state, auth) {
            for (let key of Object.keys(auth)) {
                state.auth[key] = auth[key] !== null ? auth[key] : '';
            }
            if ('avatar' in auth)
                state.auth.avatar = auth.avatar !== null ? auth.avatar : '';
            state.is_auth = true;
            state.auth.roles = auth.roles;
        },
        resetAuthUserDetail(state) {
            for (let key of Object.keys(state.auth)) {
                state.auth[key] = '';
            }
            state.is_auth = false;
            state.auth.roles = [];
            state.last_activity = null;
            localStorage.removeItem('auth_token');
            axios.defaults.headers.common['Authorization'] = null;
        },
        setConfig(state, config) {
            for (let key of Object.keys(config)) {
                state.config[key] = config[key];
            }
        },
        resetConfig(state) {
            for (let key of Object.keys(state.config)) {
                state.config[key] = '';
            }
        },
        setPermission(state, data) {
            state.permissions = [];
            data.forEach(permission => state.permissions.push(permission.name));
        },
        setTwoFactorCode(state, data) {
            state.two_factor_code = data;
        },
        resetTwoFactorCode(state) {
            state.two_factor_code = null;
        },
        setLastActivity(state) {
            state.last_activity = moment().format();
        },
        setDefaultRole(state, data) {
            state.default_role = data;
        },
        setSearchQuery(state, data) {
            state.search_query = data;
        },
        setSearchCategory(state, data) {
            state.search_category_id = data;
        }
    },
    actions,
    getters,
    plugins: [
        createPersistedState({storage: window.sessionStorage})
    ]
});

export default store;
