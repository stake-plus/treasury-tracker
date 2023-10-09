const { stringToHex, u8aToHex } = require("@polkadot/util");
const { ApiPromise, WsProvider } = require("@polkadot/api");
const { web3Accounts, web3Enable, web3FromAddress, web3FromSource } = require("@polkadot/extension-dapp");
const { cryptoWaitReady, decodeAddress, signatureVerify } = require("@polkadot/util-crypto");

async function init(){
  const wsProvider = new WsProvider('wss://rpc.dotters.network');
  const api = await ApiPromise.create({ provider: wsProvider });

  console.log(api.genesisHash.toHex());
}

const isValidSignature = (signedMessage, signature, address) => {
  const publicKey = decodeAddress(address);
  const hexPublicKey = u8aToHex(publicKey);

  return signatureVerify(signedMessage, signature, hexPublicKey).isValid;
};

async function connect(){
  const extensions = await web3Enable("opengov-platform");

  if(extensions.length === 0) return;

  const accounts = await web3Accounts();

  const select = document.getElementById('address');
  // remove any existing options
  select.innerHTML = '';
  accounts.forEach(account => {
    const option = document.createElement('option');
    option.value = account.address;
    option.text = account.meta.name;
    select.appendChild(option);
  });

  const account = accounts[1];
  console.log(JSON.stringify(accounts, null, 2));
  
}

async function sign(){
  const extensions = await web3Enable("opengov-platform");

  if(extensions.length === 0) return;

  // Get the selected account address from the dropdown
  const select = document.getElementById('address');
  const selectedAddress = select.value;
  
  const accounts = await web3Accounts();

  // Find the account from accounts that matches the selectedAddress
  const account = accounts.find(account => account.address === selectedAddress);

  if (!account) {
    console.log('No account found for the selected address');
    return;
  }

  console.log(JSON.stringify(account, null, 2));

  const injector = await web3FromSource(account.meta.source);
  const signRaw = injector?.signer?.signRaw;

  if (!!signRaw) {
    const { signature } = await signRaw({
        address: account.address,
        data: stringToHex('Just keep shaaaakkiiiin'),
        type: 'bytes'
    });

    const isValid = await verify('Just keep shaaaakkiiiin', signature, account.address);

    if (isValid) {
      // Populating the form with account address and signature
      document.getElementById('addressField').value = account.address;
      document.getElementById('signatureField').value = signature;

      // Automatically submit the form
      document.getElementById('registerForm').submit();
    } else {
      // Display an error message
      console.error('Invalid signature');
    }
  }  
}

async function verify(signedMessage, signature, hexPublicKey){
  await cryptoWaitReady();
  console.log("Signature: " + signature + " public key: " + hexPublicKey);
  const isValid = isValidSignature(
    signedMessage,
    signature,
    hexPublicKey
  );

  //If signature is valid, isValid should return true
  console.log("validity: " + isValid);
  return isValid; // return the validity
}

document.getElementById('register').addEventListener('click', sign);

var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function openModal() {
    modal.style.display = "block";
}

document.getElementById('connect').addEventListener('click', openModal);
document.getElementById('connectJS').addEventListener('click', connect);

window.onload = function() {
    setTimeout(function() {
        var buttons = document.querySelectorAll('.nav-link');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                window.location.href = this.getAttribute('data-href');
            });
        });

        var buttons = document.querySelectorAll('.table-link');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                window.location.href = this.getAttribute('data-href');
            });
        });

        const status = document.getElementById('loggedin');
        if (status.value === '1') {
            connect(); 
        }
    }, 100);
}


$(document).ready(function() {
    $('#category-dropdown').change(function() {
        var classificationId = $(this).val();
        var referendumId = $('#referendum-id').val();

        $.ajax({
            url: '/api/ajax',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('[name="_csrfToken"]').val()
            },
            contentType: 'application/json',
            data: JSON.stringify({ classificationId: classificationId, referendumId: referendumId }),
            success: function(response) {
                // Show success icon
                $('#success-icon').show();

                // Hide success icon after 5 seconds
                setTimeout(function() {
                    $('#success-icon').hide();
                }, 5000);

                // Log the full response to the console
                console.log('Server Response:', response);
            },
            error: function(error) {
                // handle error
                console.log('Error:', error);
            }
        });
    });
});
