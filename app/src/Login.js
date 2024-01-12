import React, { useState } from "react";

function Login({ onLogin, showAlert }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  async function handleSubmit(event) {
    event.preventDefault();
    let data = {};
    data['username'] = username;
    data['password'] = password;
    fetch(process.env.REACT_APP_API_HOST+'/token', {
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'Content-Type': 'application/json' },
    }).then(
        (response) => {
            if(response.status == 200)
                return response.json()
            else throw new Error('Couldn\'t authenticate')
        }).then((data) => {
            onLogin(data.token);
        })
    .catch((err) => {
        console.log(err);
        showAlert(err);
    });
  }

  return (
    <div class="container-sm">
        <form onSubmit={handleSubmit}>
            <label for="username" class="form-label">
                Username:
            </label>
            <input
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                class="form-control"
            />
            
            <br />
            <label for="password" class="form-label">
                Password:
            </label>
            <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                class="form-control"
            />
            <br />
            <input type="submit" value="Submit" class="btn btn-primary" />
        </form>
    </div>
  );
}

export default Login;