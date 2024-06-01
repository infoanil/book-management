export default function auth({next, to}){
    let isLoggedIn = localStorage.getItem("token");

    if(!isLoggedIn){
        return next({
            name: 'login'
        })
    }

    return next();
}
