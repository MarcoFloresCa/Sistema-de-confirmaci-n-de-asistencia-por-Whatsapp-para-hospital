const qrcode = require('qrcode-terminal');
const fs = require('fs');
const { Client } = require('whatsapp-web.js');
const mysql = require('mysql');

const client = new Client({
    webVersion: '2.2408.1',
    webVersionCache: { type: "local" }
});

let connection;

function connect() {
    connection = mysql.createConnection({
        host: 'localhost',
        port: '3306',
        user: 'root',
        password: '',
        database: 'somecloud'
    });
    connection.connect();
}

client.on('qr', (qr) => {
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('Client is ready!');
    connect();
    sendMessage();
    setInterval(sendMessage, 60000);
});

client.on('message', async (message) => {
    console.log('Enviando mensaje');
    console.log(message);
    if (message.body.toLowerCase() === 'si' || message.body.toLowerCase() === 'no') {
        const logData = `${message.from.split('@')[0]}: ${message.body}\n`;

        fs.appendFile('log.txt', logData, (err) => {
            if (err) {
                console.error('Error al escribir en el archivo de registro:', err);
            } else {
                console.log('Mensaje registrado en log.txt');
            }
        });
    }

    if (message.body.toLowerCase() === 'si') {
        await message.reply('Gracias por confirmar');
        let numero = message.from.split('@')[0];
        numero = numero.substring(3);
        console.log(numero);
        const update = 'UPDATE hora SET Asistencia = "Confirmado" WHERE Rut_Paciente IN (SELECT Rut FROM paciente WHERE Telefono = ' + numero + ' ) AND Asistencia = "Enviado";';
        connection.query(update, (error, results) => {
            if (error) {
                console.error('Error realizando la consulta de actualización:', error);
                return;
            }
            console.log('Registro actualizado correctamente');
        });
    } else if (message.body.toLowerCase() === 'no') {
        await message.reply('Gracias por informarnos');
        let numero = message.from.split('@')[0];
        numero = numero.substring(3);
        console.log(numero);
        const update = 'UPDATE hora SET Asistencia = "Cancelado" WHERE Rut_Paciente IN (SELECT Rut FROM paciente WHERE Telefono = ' + numero + ' ) AND Asistencia = "Enviado";';
        connection.query(update, (error, results) => {
            if (error) {
                console.error('Error realizando la consulta de actualización:', error);
                return;
            }
            console.log('Registro actualizado correctamente');
        });
    }
});

async function sendMessage() {
    const currentChileTime = new Date().toLocaleString("en-US", {timeZone: "America/Santiago"});
    const currentTime = new Date(currentChileTime);
    const currentTimeHours = currentTime.getHours();
    const currentTimeMinutes = currentTime.getMinutes();
    
    if (currentTimeHours >= 23 && currentTimeMinutes >= 59) {
        const updateNoResponse = `UPDATE hora SET Asistencia = 'No ha respondido' WHERE Asistencia = 'Enviado';`;
        connection.query(updateNoResponse, (error, results) => {
            if (error) {
                console.error('Error realizando la consulta de actualización:', error);
                return;
            }
            console.log('Registros actualizados a "No ha respondido" correctamente');
        });
    } else {
        console.log('Todavía no es el momento de actualizar los registros');
    }

    const query = "SELECT paciente.Rut as Rut, paciente.Nombre as NombrePaciente, hora.Profesional, hora.Tipo_Atencion, hora.Dia, hora.Hora_Agandada, paciente.Telefono FROM paciente INNER JOIN hora ON paciente.Rut = hora.Rut_Paciente WHERE hora.Asistencia = 'Por Confirmar';";
    connection.query(query, async function (error, results, fields) {
        if (error) {
            console.error('Error al ejecutar la consulta:', error);
            return;
        }

        let i = 0;
        for (const result of results) {
            const RutPaciente = result.Rut;
            const nombrePaciente = result.NombrePaciente;
            const nombreDoctor = result.Profesional;
            const tipoConsulta = result.Tipo_Atencion;
            const dia = result.Dia;
            const horaAgendada = result.Hora_Agandada;
            const phoneNumberWithPrefix = '569' + result.Telefono;
            const saludo = nombrePaciente.split(" ")[0];
            const chatId = phoneNumberWithPrefix + '@c.us';
            const text = `HOLA ${saludo}, LE RECORDAMOS QUE MAÑANA ${dia} A LAS ${horaAgendada} TIENE HORA PARA ${tipoConsulta} CON EL PROFESIONAL ${nombreDoctor}. PARA CONFIRMAR RESPONDA "SI" O "NO".`;

            try {
                await client.sendMessage(chatId, text);
                console.log('Mensaje enviado a', phoneNumberWithPrefix);
                let fecha = new Date();
                let fechaFormateada = fecha.toISOString().slice(0, 19).replace('T', ' ');

                const update = `UPDATE hora SET Asistencia = 'Enviado', fecha_envio = '${fechaFormateada}' WHERE hora.Rut_paciente = '${RutPaciente}' AND hora.Asistencia = 'Por Confirmar';`;
                connection.query(update, (error, results) => {
                    if (error) {
                        console.error('Error realizando la consulta de actualización:', error);
                        return;
                    }
                    console.log('Registro actualizado correctamente');
                });

                i++;
                if (i % 1 === 0) {
                    await new Promise(resolve => setTimeout(resolve, Math.floor(Math.random() * (8 - 3 + 1)) + 3) * 1000);
                }

            } catch (error) {
                console.error('Error al enviar el mensaje a', phoneNumberWithPrefix, error);
            }
        }
    });
}

client.initialize();