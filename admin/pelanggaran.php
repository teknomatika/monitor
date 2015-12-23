<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Pelanggaran <?=tbl_tambah('Input Pelanggaran','?hal=pelanggaran&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th>Nama Siswa</th>
			<th>Jenis Pelanggaran</th>
			<th class="col-lg-1 text-center">Poin</th>
			<th class="col-lg-2 text-center">Waktu</th>
			<!--<th class="col-lg-1 text-center">#</th>-->
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('pelanggaran','*',null,null,'tanggal desc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td class="text-center"><?=konvert2('siswa','nis',$d['idsiswa'],'nama');?></td>
			<td><?=konvert('tata',$d['idtata'],'nama');?></td>
			<td class="text-center"><?=konvert('tata',$d['idtata'],'poin');?></td>
			<td class="text-center" title="<?=time_ago($d['tanggal']);?>"><?=tanggal($d['tanggal']);?></td>
			<!--<td class="text-center"><?=tbl_hapus('?hal=pelanggaran&act=hapus&n='.$d['idsiswa'].'&id='.$d['id']);?></td>-->
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			echo '<h1 class="page-header">Tambah Data Pelanggaran <small>| <a href="?hal=pelanggaran">Kembali</a></small></h1>';

			if(isset($_POST['nis'])){
				echo "Processing...";
				$nis = $db->escapeString($_POST['nis']);
				$tanggal = $db->escapeString($_POST['tanggal']);
				$idtata = $db->escapeString($_POST['idtata']);
				$poin = $db->escapeString($_POST['poin']);
				$waktu = "$tanggal ".date('H:i:s');
				$uuid = id();

				$q = $db->insert('pelanggaran',array('id'=>$uuid,'idsiswa'=>$nis,'idtata'=>$idtata,'tanggal'=>$waktu));
				if($q){
					hitungpoin($nis,$uuid,'tambah');
					cektindak($nis);
					eksyen('Data berhasil diinput','?hal=pelanggaran');
				}else{
					eksyen('Data gagal diinput','?hal=pelanggaran');
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>
			<div class="col-lg-7">

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Tanggal</label>
					<div class="col-sm-9">
						<input type="text" name="tanggal" id="datepicker" class="form-control" required="required" value="<?=date('Y-m-d');?>" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">NIS</label>
					<div class="col-sm-9">
						<input type="text" name="nis" id="nis" class="form-control" required="required">
					</div>
				</div>

				<div class="form-group">
					<label for="inpuKode" class="col-sm-3 control-label">Nama Siswa</label>
					<div class="col-sm-9">
						<input type="text" name="" id="nama" class="form-control" required="required" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Kelas</label>
					<div class="col-sm-9">
						<input type="text" name="" id="kelas" class="form-control" required="required" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Wali Kelas</label>
					<div class="col-sm-9">
						<input type="text" name="" id="wali" class="form-control" required="required" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Jurusan</label>
					<div class="col-sm-9">
						<input type="text" name="" id="jurusan" class="form-control" required="required" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Kode Pelanggaran</label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">TR-0</span>
							<input type="text" name="idtata" id="idtata" class="form-control" required="required">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Pelanggaran</label>
					<div class="col-sm-9">
						<input type="text" name="" id="tatatertib" class="form-control" required="required" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-3 control-label">Poin</label>
					<div class="col-sm-9">
						<input type="text" name="poin" id="poin" class="form-control" required="required" readonly>
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-sm-9 col-sm-offset-3">
						<button type="submit" class="btn btn-primary" onClick="return confirm('Apakah Anda yakin akan melakukan aksi ini? Aksi ini tidak dapat dikembalikan.');">Simpan</button>
						<button type="reset" class="btn btn-default">Reset</button>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div id="foto"></div>
			</div>
			</form>
			<?php break;

		case 'hapus':
			echo '<h1 class="page-header">Ubah Data Pelanggaran</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			$n = mysql_real_escape_string($_GET['n']);
			hitungpoin($n,$id,'kurang');
			$db->delete('pelanggaran',"id='$id'");
			
			$res = $db->getResult();
			eksyen('','?hal=pelanggaran');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}