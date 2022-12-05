const mqtt = require('mqtt')
const host = 'broker.hivemq.com'
const port = '1883'
const connectUrl = `mqtt://${host}:${port}`
const client = mqtt.connect(connectUrl, {
    clean: true,
    connectTimeout: 4000,
    reconnectPeriod: 1000,
})

client.on('connect', function () {
  client.subscribe('php-mqtt/client/test/pasien', function (err) {
    // if (!err) {
    //   client.publish('php-mqtt/client/test', 'Hello mqtt')
    // }
  })

  // var result = [];

client.on('message', function (topic, message) {
  // message is Buffer
  console.log(message.toString())
})

client.end()
// console.log(result);
})

