<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Simple SPA</title>
    <style>
      h1 {
        margin: 2em 0;
      }
    </style>
    <!-- widget stuff here -->
    <!-- Latest CDN production Javascript and CSS -->
    <script src="https://global.oktacdn.com/okta-signin-widget/7.9.1/js/okta-sign-in.min.js" type="text/javascript"></script>
    <link href="https://global.oktacdn.com/okta-signin-widget/7.9.1/css/okta-sign-in.min.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <div class="container">
      <h1 class="text-center">Simple SPA</h1>
      <div id="messageBox" class="jumbotron">
        You are not logged in.
      </div>
      <!-- where the sign-in form appears -->
      <div id="okta-login-container"></div>
      <button id="logout" class="button" onclick="logout()" style="display: none">Logout</button>
    </div>
    <script type="text/javascript">
      var oktaConfig = {
        issuer: "https://realogy.oktapreview.com/oauth2/ausdtpyw647fbrcPi0h7",
        redirectUri: 'https://dev-okta-widget-example.azurewebsites.net/index.php',
        clientId: "0oa1t6rycn9mQgyOF0h8",
        useClassicEngine: true
      }
      // Search for URL Parameters to see if a user is being routed to the application to recover password
      var searchParams = new URL(window.location.href).searchParams;
      oktaConfig.otp = searchParams.get('otp');
      oktaConfig.state = searchParams.get('state');

     const oktaSignIn = new OktaSignIn(oktaConfig);

     // Render the login form.
     /*oktaSignIn.renderEl({
       el: '#okta-login-container'
        }, function success(res) {
          // Nothing to do in this case, the widget will automatically redirect
          // the user to Okta for authentication, then back to this page if successful
          console.log(res);
        },
        function error(err) {
          document.getElementById("messageBox").innerHTML =
            "Couldn't render the login form, something horrible must have happened. Please refresh the page.";
        }
        );  */

      oktaSignIn.authClient.token.getUserInfo().then(function(user) {
        document.getElementById("messageBox").innerHTML = "Hello, " + user.email + "! You are *still* logged in! :)";
        document.getElementById("logout").style.display = 'block';
      }, function(error) {
        oktaSignIn.showSignInToGetTokens({
          el: '#okta-login-container'
        }).then(function(tokens) {
          oktaSignIn.authClient.tokenManager.setTokens(tokens);
          oktaSignIn.remove();

          const idToken = tokens.idToken;
          document.getElementById("messageBox").innerHTML = "Hello, " + idToken.claims.email + "! You just logged in! :)";
          document.getElementById("logout").style.display = 'block';

        }).catch(function(err) {
          console.error(err);
        });
      });

      function logout() {
        oktaSignIn.authClient.signOut();
        location.reload();
      }
    </script>
  </body>
</html>
