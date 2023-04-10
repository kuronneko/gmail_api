<!--
Copyright 2018 Google LLC
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
    https://www.apache.org/licenses/LICENSE-2.0
Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->
<!-- [START gmail_quickstart] -->
<!DOCTYPE html>
<html>
  <head>
    <title>Gmail API Quickstart</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Gmail API Quickstart</p>

    <!--Add buttons to initiate auth sequence and sign out-->
    <button id="authorize_button" onclick="handleAuthClick()">Authorize</button>
    <button id="signout_button" onclick="handleSignoutClick()">Sign Out</button>

    <pre id="content" style="white-space: pre-wrap;"></pre>

    <script type="text/javascript">
      /* exported gapiLoaded */
      /* exported gisLoaded */
      /* exported handleAuthClick */
      /* exported handleSignoutClick */

      // TODO(developer): Set to client ID and API key from the Developer Console
      const CLIENT_ID = '586619828357-8afjhrkhe869fr17kg2lgo5pvqm7a4iu.apps.googleusercontent.com';
      const API_KEY = 'AIzaSyD3oQKfKOmsUVJhbY24UcSvVAlGDbmnjuA';

      // Discovery doc URL for APIs used by the quickstart
      const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest';

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      const SCOPES = 'https://www.googleapis.com/auth/gmail.readonly';

      let tokenClient;
      let gapiInited = false;
      let gisInited = false;

      document.getElementById('authorize_button').style.visibility = 'hidden';
      document.getElementById('signout_button').style.visibility = 'hidden';

      /**
       * Callback after api.js is loaded.
       */
      function gapiLoaded() {
        gapi.load('client', initializeGapiClient);
      }

      /**
       * Callback after the API client is loaded. Loads the
       * discovery doc to initialize the API.
       */
      async function initializeGapiClient() {
        await gapi.client.init({
          apiKey: API_KEY,
          discoveryDocs: [DISCOVERY_DOC],
        });
        gapiInited = true;
        maybeEnableButtons();
      }

      /**
       * Callback after Google Identity Services are loaded.
       */
      function gisLoaded() {
        tokenClient = google.accounts.oauth2.initTokenClient({
          client_id: CLIENT_ID,
          scope: SCOPES,
          callback: '', // defined later
        });
        gisInited = true;
        maybeEnableButtons();
      }

      /**
       * Enables user interaction after all libraries are loaded.
       */
      function maybeEnableButtons() {
        if (gapiInited && gisInited) {
          document.getElementById('authorize_button').style.visibility = 'visible';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick() {
        tokenClient.callback = async (resp) => {
          if (resp.error !== undefined) {
            throw (resp);
          }
          document.getElementById('signout_button').style.visibility = 'visible';
          document.getElementById('authorize_button').innerText = 'Refresh';
          //await listLabels();
          await showAllEmails();
        };

        if (gapi.client.getToken() === null) {
          // Prompt the user to select a Google Account and ask for consent to share their data
          // when establishing a new session.
          tokenClient.requestAccessToken({prompt: 'consent'});
        } else {
          // Skip display of account chooser and consent dialog for an existing session.
          tokenClient.requestAccessToken({prompt: ''});
        }
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick() {
        const token = gapi.client.getToken();
        if (token !== null) {
          google.accounts.oauth2.revoke(token.access_token);
          gapi.client.setToken('');
          document.getElementById('content').innerText = '';
          document.getElementById('authorize_button').innerText = 'Authorize';
          document.getElementById('signout_button').style.visibility = 'hidden';
        }
      }

      /**
       * Print all Labels in the authorized user's inbox. If no labels
       * are found an appropriate message is printed.
       */
      async function listLabels() {
        let response;
        try {
          response = await gapi.client.gmail.users.labels.list({
            'userId': 'me',
          });
        } catch (err) {
          document.getElementById('content').innerText = err.message;
          return;
        }
        const labels = response.result.labels;
        if (!labels || labels.length == 0) {
          document.getElementById('content').innerText = 'No labels found.';
          return;
        }
        // Flatten to string to display
        const output = labels.reduce(
            (str, label) => `${str}${label.name}\n`,
            'Labels:\n');
        document.getElementById('content').innerText = output;
      }





      async function showAllEmails() {
  let response;
  try {
    response = await gapi.client.gmail.users.messages.list({
      'userId': 'me',
      'maxResults': 10, // change to the number of emails you want to retrieve
    });
  } catch (err) {
    console.error(err);
    document.getElementById('email-content').innerText = 'An error occurred while retrieving the emails.';
    return;
  }
  const messages = response.result.messages;
  if (!messages || messages.length === 0) {
    console.log('No messages found.');
    document.getElementById('email-content').innerText = 'No messages found.';
    return;
  }
  const emails = [];
  for (const message of messages) {
    try {
      response = await gapi.client.gmail.users.messages.get({
        'userId': 'me',
        'id': message.id,
        'format': 'full', // Retrieve full message including attachments
      });
    } catch (err) {
      console.error(err);
      document.getElementById('email-content').innerText = 'An error occurred while retrieving the email.';
      return;
    }
    const headers = response.result.payload.headers;
    const subject = headers.find(header => header.name === 'Subject');
    const from = headers.find(header => header.name === 'From');
    const date = headers.find(header => header.name === 'Date');
    const body = response.result.snippet;
    const attachments = [];
    const payload = response.result.payload;
    if (payload.parts && payload.parts.length > 0) {
      for (let i = 0; i < payload.parts.length; i++) {
        const part = payload.parts[i];
        if (part.filename && part.filename.length > 0) {
          // This part is an attachment
          attachments.push(part);
        }
      }
    }
    const emailHtml = `
      <div>
        <button onclick="showEmail('${message.id}')">Show Email</button>
        <span><strong>From:</strong> ${from.value}</span>
        <span><strong>Subject:</strong> ${subject.value}</span>
      </div>
      <div id="${message.id}" style="display:none">
        <p><strong>Subject:</strong> ${subject.value}</p>
        <p><strong>From:</strong> ${from.value}</p>
        <p><strong>Date:</strong> ${date.value}</p>
        <p><strong>Body:</strong> ${body}</p>
        ${attachments.length > 0 ? `<p><strong>Attachments:</strong> ${attachments.map(att => `<a href="#" onclick="downloadAttachment('${message.id}', '${att.body.attachmentId}', '${att.filename}')">${att.filename}</a>`).join(', ')}</p>` : ''}
      </div>
    `;
    emails.push(emailHtml);
  }
  document.getElementById('email-content').innerHTML = emails.join('');
}

function showEmail(emailId) {
  const emailDiv = document.getElementById(emailId);
  if (emailDiv.style.display === 'none') {
    emailDiv.style.display = 'block';
  } else {
    emailDiv.style.display = 'none';
  }
}

async function downloadAttachment(messageId, attachmentId, filename) {
  try {
    const response = await gapi.client.request({
      'path': `/gmail/v1/users/me/messages/${messageId}/attachments/${attachmentId}`,
      'method': 'GET',
      'params': {'format': 'raw'},
      'headers': {'Authorization': `Bearer ${gapi.auth.getToken().access_token}`},
      'responseType': 'arraybuffer',
    });

    const replacedString = response.result.data.replace(/-/g, '+').replace(/_/g, '/');

        // Convert base64 string to binary data
    const binaryData = atob(replacedString);

    // Create a Uint8Array from the binary data
    const uint8Array = new Uint8Array(binaryData.length);
    for (let i = 0; i < binaryData.length; i++) {
    uint8Array[i] = binaryData.charCodeAt(i);
    }

    // Create a blob object from the Uint8Array
    const blob = new Blob([uint8Array], { type: response.headers['Content-Type'] });
    //const blob = new Blob([replacedString], {type: response.headers['Content-Type']});

    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (err) {
    console.error(err);
    alert('An error occurred while downloading the attachment.');
  }
}




    </script>

<div id="email-content"></div>


    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
  </body>
</html>
<!-- [END gmail_quickstart] -->
