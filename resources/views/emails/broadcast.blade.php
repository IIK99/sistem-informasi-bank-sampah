<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Helvetica, Arial, sans-serif; color: #333333;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background-color: #f4f6f8; padding: 30px 15px;">
        <tr>
            <td align="center">
                <!-- Kontainer Kartu Email -->
                <table role="presentation" width="100%"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-collapse: separate;">

                    <!-- Banner Header Hijau -->
                    <tr>
                        <td style="background-color: #198754; padding: 28px 24px; text-align: center; color: #ffffff;">
                            <h2
                                style="margin: 0; font-size: 20px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">
                                BANK SAMPAH WARGA
                            </h2>
                            <p style="margin: 6px 0 0 0; font-size: 13px; opacity: 0.9;">
                                Pengumuman & Pemberitahuan Jadwal Operasional
                            </p>
                        </td>
                    </tr>

                    <!-- Isi Surat -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="margin: 0 0 16px 0; font-size: 15px; font-weight: bold; color: #111827;">
                                Halo, {{ $nasabah->name }} !
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #4b5563;">
                                Ada pengumuman penting terbaru dari pengelola Bank Sampah untuk seluruh warga dan
                                nasabah:
                            </p>

                            <!-- Kotak Pesan Pengumuman -->
                            <div
                                style="background-color: #f8fafc; border-left: 4px solid #198754; border-radius: 6px; padding: 18px 20px; margin-bottom: 24px; font-size: 14px; line-height: 1.7; color: #1f2937;">
                                {!! $pesan !!}
                            </div>

                            <p style="margin: 0 0 20px 0; font-size: 13px; line-height: 1.6; color: #6b7280;">
                                Mohon persiapkan sampah pilahan Anda (kardus, plastik, botol, atau logam) dalam kondisi
                                bersih dan kering sebelum dibawa ke pos penimbangan.
                            </p>

                            <!-- Tombol Aksi Langsung ke Portal Web -->
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 25px 0 10px 0;">
                                <tr>
                                    <td align="center" style="border-radius: 50px; background-color: #198754;">
                                        <a href="{{ url('/dashboard') }}" target="_blank"
                                            style="font-size: 13px; font-weight: bold; color: #ffffff; text-decoration: none; padding: 12px 26px; display: inline-block; border-radius: 50px;">
                                            Buka Portal Bank Sampah
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Email -->
                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 18px 24px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; line-height: 1.5;">
                            Pesan siaran resmi ini dikirimkan secara otomatis oleh <strong>Sistem Informasi Bank
                                Sampah</strong>.<br>
                            &copy; {{ date('Y') }} Bank Sampah — Lingkungan Bersih, Rezeki Bertambah.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
