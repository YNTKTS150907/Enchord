const express = require('express');
const mysql = require('mysql2/promise');
const bodyParser = require('body-parser');

const app = express();

// Middleware untuk parsing form data
app.use(bodyParser.urlencoded({ extended: false }));

// (Anti-XSS) bisa dibilang kek gitu
function sanitizeInput(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

// Endpoint form submission
app.post('/proses', async (req, res) => {
    try {
        if (!req.body.nama || !req.body.kelas) {
            return res.status(400).send("Nama dan kelas harus diisi!");
        }

        // Sanitizing input
        const nama = sanitizeInput(req.body.nama);
        const kelas = sanitizeInput(req.body.kelas);

        // Connection ke database
        const connection = await mysql.createConnection({
            host: 'localhost',
            user: 'root',
            password: '',
            database: 'sekolah',
            charset: 'utf8mb4'
        });

        // Prepared statement untuk menghindari SQL Injection
        const [results] = await connection.execute(
            "INSERT INTO siswa (nama, kelas) VALUES (?, ?)",
            [nama, kelas]
        );

        // Close
        await connection.end();

        // Redirect
        res.redirect('/sukses?status=berhasil');

    } catch (error) {
        console.error("Error database:", error); // Log error di server
        res.status(500).send("Terjadi kesalahan saat menyimpan data");
    }
});

// Endpoint untuk page sukses
app.get('/sukses', (req, res) => {
    if (req.query.status === 'berhasil') {
        res.send("Data tersimpan!");
    } else {
        res.redirect('/');
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server berjalan di http://localhost:${PORT}`);
});