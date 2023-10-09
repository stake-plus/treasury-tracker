#!/bin/bash

# Use sed to replace the line
sed -i "s/app.listen(3000, 'localhost', () => console.log('Server running on port 3000 and listening only on localhost'));/app.listen(3000, '0.0.0.0', () => console.log('Server running on port 3000 and listening only on all interfaces'));/g" /usr/src/app/polkadotjs-proxy/server.js


