<!--Ukuran Sedang-->
<!DOCTYPE html>
<html>
<head>
	<title>Bukti Pendaftaran</title>
    <link href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
</head>
<body>
	<div class="container">
		<div class="row justify-content-start">
			<div class="col-md-7">
				<div class="form-group text-center">
					<h4><p><b>H-Clinic Tulungagung</b></p></h4>
					<h6>
						<p>Jl. Mayor Sujadi Timur no.7 Tulungagung</p>
						<p>E-mail : h-clinic@gmail.com - Website : http://h-clinic.com</p>
					</h6>
					<hr />
					<h5><u><b>BUKTI PENDAFTARAN MEMBER</b></u></h5><br />
				</div>
				<div class="content">
					<table width="100%">
						<tr>
							<th>Kode Member</th>
							<td>:</td>
							<td><?= $r_member->kode ?></td>
						</tr>
						<tr>
							<th>Data Jenis Member</th>
							<td>:</td>
							<td><?= $r_member->jenis_member ?></td>
						</tr>
					</table>
					<br />
			        <table class="table table-bordered">
			            <tr>
			                <th>Nama</th>
			                <td><?= $r_member->nama ?></td>
			            </tr>
			            <tr>
			                <th>Jenis Identitas</th>
			                <td><?= $r_member->jenis_identitas ?></td>
			            </tr>
			            <tr>
			                <th>Jenis Kelamin</th>
			                <td><?= $this->member_m->enum('jenis_kelamin', $r_member->jenis_kelamin) ?></td>
			            </tr>
			            <tr>
			                <th>Telepon</th>
			                <td><?= $r_member->telepon ?></td>
			            </tr>
			            <tr>
			                <th>HP</th>
			                <td><?= $r_member->hp ?></td>
			            </tr>
			            <tr>
			                <th>Alamat</th>
			                <td><?= $r_member->alamat ?></td>
			            </tr>
			        </table>
					<table width="100%">
						<tr>
							<th>Tanggal Daftar</th>
							<td>:</td>
							<td><?= date('d-m-Y', strtotime($post['tanggal_daftar'])) ?></td>
						</tr>
						<tr>
							<th>Tanggal Expired</th>
							<td>:</td>
							<td><?= date('d-m-Y', strtotime($post['tanggal_expired'])) ?></td>
						</tr>
						<tr>
							<th>Diskon</th>
							<td>:</td>
							<td><?= $post['diskon_persen'] ?>%</td>
						</tr>
						<tr>
							<th>Convert Diskon</th>
							<td>:</td>
							<td><?= $post['diskon'] ?></td>
						</tr>
						<tr>
							<th>PPN</th>
							<td>:</td>
							<td><?= $post['ppn'] ?></td>
						</tr>
						<tr>
							<th>Harga</th>
							<td>:</td>
							<td><?= $post['biaya'] ?></td>
						</tr>
						<tr>
							<th>Total</th>
							<td>:</td>
							<td style="color: #e00808;"><h4><b><?= $post['total'] ?></b></h4></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>
		window.print();
	</script>
</body>
</html>