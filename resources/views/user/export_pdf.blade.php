<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            font-size: 12pt;
        }

        th {
            background-color: #3f51b5;
            color: white;
            text-align: center;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover td {
            background-color: #e1f5fe;
        }

        .text-center {
            text-align: center;
        }

        .header-table {
            border-bottom: 2px solid #3f51b5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .font-bold {
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 18pt;
            color: #333;
            margin: 20px 0;
        }

        .logo {
            width: 90px;
            height: auto;
        }

        .info-header {
            font-size: 10pt;
            color: #333;
        }

        .bordered {
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="title">LAPORAN DATA USER</div>

    <div class="bordered">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level Pengguna</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user as $u)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->nama }}</td>
                        <td class="text-center">{{ $u->level->level_nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
