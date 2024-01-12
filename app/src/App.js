import React from "react";
import { CookiesProvider, useCookies } from "react-cookie";
import Login from "./Login";
import Products from "./Products";

export default function App() {
  const [cookies, setCookie] = useCookies(["token"]);

  function nextHour(date){
    const dateCopy = new Date(date);
    dateCopy.setTime(dateCopy.getTime() + 60 * 60 * 1000);
    return dateCopy;
  }

  function handleLogin(token) {
    if(token != undefined){
      setCookie("token", token, { path: "/" });
      setCookie("tokenExpiration", nextHour(new Date()).getTime(), { path: "/" });
    }
  }

  function activeToken() {
    if(!cookies.token)
      return false;
    if(cookies.tokenExpiration && new Date().getTime() > cookies.tokenExpiration)
      return false
    return true;
  }

  function showAlert(message) {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
      `<div class="alert alert-danger alert-dismissible" role="alert">`,
      `   <div>${message}</div>`,
      '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
      '</div>'
    ].join('')

    alertPlaceholder.append(wrapper)
  }

  return (
    <>
      <div id="header" class="container text-center px-4">
        <h1>Aaxis Test Application</h1>
      </div>
      <div id="main">
        <div id="liveAlertPlaceholder"></div>
        <CookiesProvider>
          <div>
            {activeToken() ? (
              <Products token={cookies.token} showAlert={showAlert} />
            ) : (
              <Login onLogin={handleLogin} showAlert={showAlert} />
            )}
          </div>
        </CookiesProvider>
      </div>
    </>
  );
}

