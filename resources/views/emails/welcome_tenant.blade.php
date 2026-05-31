<x-mail::message>
# Halo, {{ $user->name ?? 'Admin' }}!

Selamat! Pembayaran untuk sistem Anda **({{ $tenant->name }})** telah berhasil dikonfirmasi. Layanan (sistem/aplikasi) Anda kini telah **Aktif** dan dapat digunakan.

Untuk langkah pertama, silakan klik tombol di bawah ini untuk mengatur password akun admin Anda.

<x-mail::button :url="$resetUrl">
Setup Password Anda
</x-mail::button>

Jika Anda mengalami kesulitan, silakan hubungi tim dukungan kami.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
