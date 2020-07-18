<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fifo_m extends CI_Model {

    protected $id_gudang;

    public function __construct() {
        $this->load->model('cabang_gudang_m');
        $this->load->model('barang_m');
        $this->load->model('konversi_satuan_m');
        $this->load->model('barang_stok_m');
        $this->load->model('barang_stok_mutasi_m');
        $this->load->model('barang_stok_periode_m');
        $this->load->model('views/view_hpp_keluar_m');

        if ($this->session->userdata('cabang')) {
            $this->id_gudang = $this->cabang_gudang_m->scope('utama')
                ->where('id_cabang', $this->session->userdata('cabang')->id)
                ->first()->id_gudang;
        }
    }

    public function set_gudang($id_gudang) {
        $this->id_gudang = $id_gudang;
    }

    public function insert($tipe, $record) {
        $barang = $this->barang_m->find_or_fail($record['id_barang']);
        $record['id_rak_gudang'] = $barang->id_rak_gudang;
        if ($tipe == 'masuk') {
            $mutasi = $this->masuk($record);
        } else if ($tipe == 'keluar') {
            $mutasi = $this->keluar($record);
        }

        $barang_stok_periode = $this->barang_stok_periode_m->where('periode', date('Y-m', strtotime($record['tanggal_mutasi'])))
            ->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $record['id_barang'])
            ->first();

        $barang_stok_periode_awal = $this->barang_stok_periode_m->where('periode < ', date('Y-m', strtotime($record['tanggal_mutasi'])))
            ->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $record['id_barang'])
            ->order_by('periode', 'DESC')
            ->first();

        $total_mutasi = $this->barang_stok_mutasi_m->select("
                barang_stok_mutasi.id_gudang,
                barang_stok_mutasi.id_rak_gudang,
                barang_stok_mutasi.id_barang,
                barang_stok_mutasi.id_satuan,
                MAX(barang_stok_mutasi.tanggal_mutasi) AS tanggal_mutasi,
                barang_stok.tanggal_masuk_terakhir,
                barang_stok.tanggal_keluar_terakhir,
                MAX(CASE WHEN tipe_mutasi = 'keluar' THEN barang_stok_mutasi.index_akhir + 1 ELSE 1 END) AS index_awal,
                MAX(CASE WHEN tipe_mutasi = 'masuk' THEN barang_stok_mutasi.index_akhir ELSE 0 END) AS index_akhir
            ")
            ->join('barang_stok', '(barang_stok.id_gudang = barang_stok_mutasi.id_gudang AND barang_stok.id_barang = barang_stok_mutasi.id_barang)')
            ->where('LEFT(barang_stok_mutasi.tanggal_mutasi, 7) <= ', date('Y-m', strtotime($mutasi->tanggal_mutasi)))
            ->where('barang_stok_mutasi.id_gudang', $this->id_gudang)
            ->where('barang_stok_mutasi.id_barang', $mutasi->id_barang)
            ->first();

        $tanggal_masuk_terakhir = $total_mutasi->tanggal_masuk_terakhir;
        $tanggal_keluar_terakhir = $total_mutasi->tanggal_keluar_terakhir;
        $jumlah = ($total_mutasi->index_akhir - $total_mutasi->index_awal) + 1;

        if ($tipe == 'masuk' && $mutasi->tanggal_mutasi < $total_mutasi->tanggal_masuk_terakhir) {
            $tanggal_masuk_terakhir = $mutasi->tanggal_mutasi;
        } else if ($tipe == 'keluar' && $mutasi->tanggal_mutasi < $total_mutasi->tanggal_keluar_terakhir) {
            $tanggal_keluar_terakhir = $mutasi->tanggal_mutasi;
        }

        /*if ($barang_stok_periode_awal) {
            $tanggal_masuk_terakhir = $barang_stok_periode_awal->tanggal_masuk_terakhir;
            $tanggal_keluar_terakhir = $barang_stok_periode_awal->tanggal_keluar_terakhir;
            $jumlah += $barang_stok_periode_awal->jumlah;
        }*/

        $record_stok_periode = array(
            'periode' => date('Y-m', strtotime($total_mutasi->tanggal_mutasi)),
            'id_gudang' => $this->id_gudang,
            'id_rak_gudang' => $barang->id_rak_gudang,
            'id_barang' => $total_mutasi->id_barang,
            'id_satuan' => $total_mutasi->id_satuan,
            'index_awal' => $total_mutasi->index_awal,
            'index_akhir' => $total_mutasi->index_akhir,
            'tanggal_masuk_terakhir' => $tanggal_masuk_terakhir,
            'tanggal_keluar_terakhir' => $tanggal_keluar_terakhir,
            'jumlah' => $jumlah
        );

        if ($barang_stok_periode) {
            $this->barang_stok_periode_m->update($barang_stok_periode->id, $record_stok_periode);
        } else {
            $this->barang_stok_periode_m->insert($record_stok_periode);
        }

        if ($tipe == 'masuk') {
            $this->db->set('index_akhir', 'index_akhir + '.$mutasi->jumlah, false)
                ->set('jumlah', 'jumlah + '.$mutasi->jumlah, false);
        } else if ($tipe == 'keluar' ) {
            $this->db->set('index_awal', 'index_awal + '.$mutasi->jumlah, false)
                ->set('jumlah', 'jumlah - '.$mutasi->jumlah, false);
        }

        $this->db->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $mutasi->id_barang)
            ->where('periode > ', date('Y-m', strtotime($mutasi->tanggal_mutasi)))
            ->update('barang_stok_periode');

        $this->check_item($mutasi->id_barang, $mutasi->tanggal_mutasi);

        return $mutasi;
    }

    private function masuk($record) {
        $barang_stok = $this->barang_stok_m->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $record['id_barang'])
            ->first();

        $konversi = $this->konversi($record['id_barang'], $record['id_satuan'], $record['jumlah']);

        $index_awal = 1;
        $index_akhir = $konversi['jumlah'];
        $tanggal_masuk_terakhir = date('Y-m-d', strtotime($record['tanggal_mutasi']));

        if ($barang_stok) {
            $this->barang_stok_m->update($barang_stok->id, array(
                'index_akhir' => $barang_stok->index_akhir + $index_akhir,
                'tanggal_masuk_terakhir' => ($barang_stok->tanggal_masuk_terakhir > $tanggal_masuk_terakhir) ? $barang_stok->tanggal_masuk_terakhir : $tanggal_masuk_terakhir,
                'jumlah' => $barang_stok->jumlah + $index_akhir
            ));

            $mutasi = $this->barang_stok_mutasi_m->select_max('index_akhir')
                ->where('id_gudang', $this->id_gudang)
                ->where('id_barang', $record['id_barang'])
                ->where('tipe_mutasi', 'masuk')
                ->where('tanggal_mutasi <= ', $tanggal_masuk_terakhir)
                ->first();

            if ($mutasi) {
                $index_awal += $mutasi->index_akhir;
                $index_akhir += $mutasi->index_akhir;
            }
        } else {
            $this->barang_stok_m->insert(array(
                'id_gudang' => $this->id_gudang,
                'id_rak_gudang' => $record['id_rak_gudang'],
                'id_barang' => $record['id_barang'],
                'id_satuan' => $konversi['id_satuan_utama'],
                'index_awal' => $index_awal,
                'index_akhir' => $index_akhir,
                'tanggal_masuk_terakhir' => $tanggal_masuk_terakhir,
                'jumlah' => $konversi['jumlah']
            ));
        }
        if ($this->localization->number_value($record['jumlah']) > 0) {
            $nilai = (($this->localization->number_value($record['total']) / $this->localization->number_value($record['jumlah'])) / $konversi['konversi']);
        } else {
            $nilai = 0;
        }
        $mutasi_masuk = $this->barang_stok_mutasi_m->insert(array(
            'tanggal_mutasi' => $record['tanggal_mutasi'],
            'tipe_mutasi' => 'masuk',
            'jenis_mutasi' => $record['jenis_mutasi'],
            'id_ref' => $record['id_ref'],
            'id_gudang' => $this->id_gudang,
            'id_rak_gudang' => $record['id_rak_gudang'],
            'id_barang' => $record['id_barang'],
            'id_satuan' => $konversi['id_satuan_utama'],
            'index_awal' => $index_awal,
            'index_akhir' => $index_akhir,
            'jumlah' => $konversi['jumlah'],
            'nilai' => $nilai,
            'total' => $this->localization->number_value($record['total']),
            'expired' => $record['expired'],
	        'batch_number' => $record['batch_number']
        ));

        $this->db->set('index_awal', 'index_awal + '.$mutasi_masuk->jumlah, false)
            ->set('index_akhir', 'index_akhir + '.$mutasi_masuk->jumlah, false)
            ->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $mutasi_masuk->id_barang)
            ->where('tipe_mutasi', 'masuk')
            ->where('tanggal_mutasi > ', $mutasi_masuk->tanggal_mutasi)
            ->update('barang_stok_mutasi');

        return $mutasi_masuk;
    }

    private function keluar($record) {
        $barang_stok = $this->barang_stok_m->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $record['id_barang'])
            ->first();

	    $konversi = $this->konversi($record['id_barang'], $record['id_satuan'], $record['jumlah']);
        $tanggal_keluar_terakhir = date('Y-m-d', strtotime($record['tanggal_mutasi']));

        $mutasi = $this->barang_stok_mutasi_m->select('COALESCE(MAX(index_akhir), 0) AS index_akhir')
            ->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $record['id_barang'])
            ->where('tipe_mutasi', 'keluar')
            ->where('tanggal_mutasi <= ', $tanggal_keluar_terakhir)
            ->first();

	    $index_awal = 1 + $mutasi->index_akhir;
	    $index_akhir = $mutasi->index_akhir + $konversi['jumlah'];

        $mutasi_keluar = $this->barang_stok_mutasi_m->insert(array(
            'tanggal_mutasi' => $record['tanggal_mutasi'],
            'tipe_mutasi' => 'keluar',
            'jenis_mutasi' => $record['jenis_mutasi'],
            'id_ref' => $record['id_ref'],
            'id_gudang' => $this->id_gudang,
            'id_rak_gudang' => $record['id_rak_gudang'],
            'id_barang' => $record['id_barang'],
            'id_satuan' => $konversi['id_satuan_utama'],
            'index_awal' => $index_awal,
            'index_akhir' => $index_akhir,
            'jumlah' => $konversi['jumlah'],
            'nilai' => 0,
            'total' => 0
        ));

        $this->db->set('index_awal', 'index_awal + '.$mutasi_keluar->jumlah, false)
            ->set('index_akhir', 'index_akhir + '.$mutasi_keluar->jumlah, false)
            ->where('id_gudang', $this->id_gudang)
            ->where('id_barang', $mutasi_keluar->id_barang)
            ->where('tipe_mutasi', 'keluar')
            ->where('tanggal_mutasi > ', $mutasi_keluar->tanggal_mutasi)
            ->update('barang_stok_mutasi');

	    if ($barang_stok) {
		    $this->barang_stok_m->update($barang_stok->id, array(
			    'index_awal' => $barang_stok->index_awal + $konversi['jumlah'],
			    'tanggal_keluar_terakhir' => ($barang_stok->tanggal_keluar_terakhir > $tanggal_keluar_terakhir) ? $barang_stok->tanggal_keluar_terakhir : $tanggal_keluar_terakhir,
			    'jumlah' => $barang_stok->jumlah - $konversi['jumlah']
		    ));
	    } else {
		    $this->barang_stok_m->insert(array(
			    'id_gudang' => $this->id_gudang,
			    'id_rak_gudang' => $record['id_rak_gudang'],
			    'id_barang' => $record['id_barang'],
			    'id_satuan' => $konversi['id_satuan_utama'],
			    'index_awal' => 1 + $konversi['jumlah'],
			    'index_akhir' => 0,
			    'tanggal_keluar_terakhir' => $tanggal_keluar_terakhir,
			    'jumlah' => 0 - $konversi['jumlah']
		    ));
	    }

        return $mutasi_keluar;
    }

    public function edit($id_ref, $tipe, $record) {
        $this->_delete($id_ref, $tipe);
        $this->insert($tipe, $record);
    }

    public function delete($id_ref, $tipe) {
        $rs_barang_stok_mutasi = $this->_delete($id_ref, $tipe);
	    if ($rs_barang_stok_mutasi) {
		    foreach ($rs_barang_stok_mutasi as $barang_stok_mutasi) {
			    $this->check_item($barang_stok_mutasi->id_barang, $barang_stok_mutasi->tanggal_mutasi);
		    }
	    }
    }

    public function _delete($id_ref, $tipe) {
	    $result = array();
        $rs_barang_stok_mutasi = $this->barang_stok_mutasi_m->where('id_ref', $id_ref)
            ->where('tipe_mutasi', $tipe)
            ->get();
	    if ($rs_barang_stok_mutasi) {
		    foreach ($rs_barang_stok_mutasi as $barang_stok_mutasi) {
			    $this->barang_stok_mutasi_m->where('id_ref', $id_ref)
				    ->where('id_barang', $barang_stok_mutasi->id_barang)
				    ->where('tipe_mutasi', $tipe)
				    ->delete();

			    if ($tipe == 'masuk') {
				    $this->db->set('index_awal', 'index_awal - '.$barang_stok_mutasi->jumlah, FALSE)
					    ->set('index_akhir', 'index_akhir - '.$barang_stok_mutasi->jumlah, FALSE)
					    ->where('tipe_mutasi', 'masuk');
			    } else if ($tipe == 'keluar') {
				    $this->db->set('index_awal', 'index_awal - '.$barang_stok_mutasi->jumlah, FALSE)
					    ->set('index_akhir', 'index_akhir - '.$barang_stok_mutasi->jumlah, FALSE)
					    ->where('tipe_mutasi', 'keluar');
			    }
			    $this->db->where('id_gudang', $barang_stok_mutasi->id_gudang)
				    ->where('id_barang', $barang_stok_mutasi->id_barang)
				    ->where('tanggal_mutasi > ', $barang_stok_mutasi->tanggal_mutasi)
				    ->update('barang_stok_mutasi');

			    if ($tipe == 'masuk') {
				    $this->db->set('index_akhir', 'index_akhir - '.$barang_stok_mutasi->jumlah, false)
					    ->set('jumlah', 'jumlah - '.$barang_stok_mutasi->jumlah, false);
			    } else if ($tipe == 'keluar') {
				    $this->db->set('index_awal', 'index_awal - '.$barang_stok_mutasi->jumlah, false)
					    ->set('jumlah', 'jumlah + '.$barang_stok_mutasi->jumlah, false);
			    }
			    $this->db->where('id_gudang', $barang_stok_mutasi->id_gudang)
				    ->where('id_barang', $barang_stok_mutasi->id_barang)
				    ->update('barang_stok');

			    if ($tipe == 'masuk') {
				    $this->db->set('index_akhir', 'index_akhir - '.$barang_stok_mutasi->jumlah, false)
					    ->set('jumlah', 'jumlah - '.$barang_stok_mutasi->jumlah, false);
			    } else if ($tipe == 'keluar') {
				    $this->db->set('index_awal', 'index_awal - '.$barang_stok_mutasi->jumlah, false)
					    ->set('jumlah', 'jumlah + '.$barang_stok_mutasi->jumlah, false);
			    }
			    $this->db->where('id_gudang', $barang_stok_mutasi->id_gudang)
				    ->where('id_barang', $barang_stok_mutasi->id_barang)
				    ->where('periode >= ', date('Y-m', strtotime($barang_stok_mutasi->tanggal_mutasi)))
				    ->update('barang_stok_periode');

			    $result[] = $barang_stok_mutasi;
		    }
	    }

        return $result;
    }

    private function check_item($id_barang, $tanggal_mutasi) {
        $barang = $this->barang_m->find_or_fail($id_barang);
        if (!$barang->minus) {
            $stok = $this->barang_stok_m->stok($tanggal_mutasi)
                ->where('barang_stok.id_gudang', $this->id_gudang)
                ->where('barang_stok.id_barang', $id_barang)
                ->first();
            if ($stok->stok_akhir < 0) {
                $this->exceptions->quantities($this);
            }
            $mutasi = $this->db->select('mutasi.*, (@sum := @sum + mutasi.jumlah_masuk - mutasi.jumlah_keluar) AS stok')
                ->from('(SELECT
                        barang_stok_mutasi.tanggal_mutasi,
                        COALESCE(mutasi_masuk.jumlah_masuk, 0) AS jumlah_masuk,
                        COALESCE(mutasi_keluar.jumlah_keluar, 0) AS jumlah_keluar
                    FROM
                    barang_stok_mutasi
                    LEFT JOIN
                        (SELECT tanggal_mutasi, tipe_mutasi, id_gudang, id_barang, SUM(jumlah) AS jumlah_masuk
                        FROM barang_stok_mutasi
                        WHERE tipe_mutasi = \'masuk\'
                        GROUP BY tanggal_mutasi, id_gudang, id_barang) mutasi_masuk
                        ON mutasi_masuk.tanggal_mutasi = barang_stok_mutasi.tanggal_mutasi
                        AND mutasi_masuk.id_gudang = barang_stok_mutasi.id_gudang
                        AND mutasi_masuk.id_barang = barang_stok_mutasi.id_barang
                    LEFT JOIN
                        (SELECT tanggal_mutasi, tipe_mutasi,id_gudang, id_barang, SUM(jumlah) AS jumlah_keluar
                        FROM barang_stok_mutasi
                        WHERE tipe_mutasi = \'keluar\'
                        GROUP BY tanggal_mutasi, id_gudang, id_barang) mutasi_keluar
                        ON mutasi_keluar.tanggal_mutasi = barang_stok_mutasi.tanggal_mutasi
                        AND mutasi_keluar.id_gudang = barang_stok_mutasi.id_gudang
                        AND mutasi_keluar.id_barang = barang_stok_mutasi.id_barang
                    CROSS JOIN
                        (SELECT @sum := '.$stok->stok_akhir.') params
                    GROUP BY barang_stok_mutasi.tanggal_mutasi, barang_stok_mutasi.id_gudang, barang_stok_mutasi.id_barang
                    ORDER BY barang_stok_mutasi.tanggal_mutasi) mutasi')
                ->where('mutasi.tanggal_mutasi > ', $tanggal_mutasi)
                ->order_by('stok')
                ->limit(1)
                ->get()
                ->row();
            if ($mutasi && $mutasi->stok < 0) {
                $this->exceptions->quantities($this);
            }
        }
    }

	private function konversi($id_barang, $id_satuan, $jumlah) {
		$r_satuan_utama = $this->barang_m->find_or_fail($id_barang);
		$konversi = 1;
		if ($r_satuan_utama->id_satuan != $id_satuan) {
			$konversi = $this->konversi_satuan_m->convert($id_satuan, $r_satuan_utama->id_satuan, 1);
		}
		$result = array(
			'id_satuan_utama' => $r_satuan_utama->id_satuan,
			'konversi' => $konversi,
			'jumlah' => $this->localization->number_value($jumlah) * $konversi
		);
		return $result;

	}
}