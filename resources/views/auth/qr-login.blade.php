<x-guest-layout>
    <div class="qr-container">
        <div class="text-center">
            <h2 class="qr-title">Scan QR Code untuk Login</h2>
            <div class="qr-code">
                {!! $qrCode !!}
            </div>
            <p class="qr-subtitle">Arahkan kamera ke QR Code untuk login menggunakan Google.</p>
        </div>
    </div>
</x-guest-layout>

<style>
    /* Container utama */
    .qr-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Tinggi penuh layar */
        background-color: #f8f9fa; /* Warna latar opsional */
        padding: 20px; /* Untuk mencegah elemen terlalu mepet di layar kecil */
    }

    /* Konten utama */
    .text-center {
        text-align: center;
    }

    .qr-title {
        margin-bottom: 20px; /* Jarak dari QR Code */
        font-size: 1.5rem; /* Ukuran font opsional */
        color: #333; /* Warna teks */
    }

    .qr-code {
        margin: 20px 0; /* Jarak atas dan bawah QR Code */
        margin-left: 70px;
    }

    .qr-subtitle {
        margin-top: 20px; /* Jarak dari QR Code */
        font-size: 1rem; /* Ukuran font opsional */
        color: #555; /* Warna teks */
    }
</style>
