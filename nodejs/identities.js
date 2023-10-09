
//Get polkadot api
const{ ApiPromise, WsProvider} = require('@polkadot/api');
const { hexToString } = require('@polkadot/util');


export async function getIdentity(network, address){
  try{
    if(network == null){
      console.log("Needs a network, e.g. Polkadot, Kusama");
      return(null);
    }
    if(address == null){
      console.log('Needs an address');
      return(null);
    }
    const provider = new WsProvider(network);
    const api = await ApiPromise.create({ provider });

    const identity = await api.query.identity.identityOf(address);

    if(identity.isSome){
      const name = hexToString(identity.unwrap().info.display.asRaw.toHex());
      const email = hexToString(identity.unwrap().info.email.asRaw.toHex());
      const website = hexToString(identity.unwrap().info.web.asRaw.toHex());
      const twitter = hexToString(identity.unwrap().info.twitter.asRaw.toHex());
      const riot = hexToString(identity.unwrap().info.riot.asRaw.toHex());

      const id_json = {
        "address": address,
        "name": name,
        "email": email,
        "website": website,
        "twitter": twitter,
        "riot": riot

      }

      return(id_json);
      
    }
  } catch(error){
    console.error(error);
    return(null);
    
  }
}



/*
const resultPromise = getIdentity('wss://kusama-rpc.polkadot.io', "HqRcfhH8VXMhuCk5JXe28WMgDDuW9MVDVNofe1nnTcefVZn");
resultPromise.then(result => {
  console.log(result);
});
*/

