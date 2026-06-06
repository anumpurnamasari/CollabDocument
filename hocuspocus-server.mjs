import { Server } from '@hocuspocus/server'

const server = new Server({
    port: 1234,
    quiet: true,
})

server.listen()

console.log('Hocuspocus server running on ws://192.168.234.215')