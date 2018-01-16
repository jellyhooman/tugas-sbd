<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require APPPATH . '/libraries/REST_Controller.php';

class Transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_security');
		$this->load->model('manajer/M_transaksi');
		$this->load->library('datatables');
	}

	public function index()
	{
		$this->M_security->cek_security();
		$level = $this->session->userdata('level');
		$key   = $this->session->userdata('id_pegawai');

		$data['judul']		= 'Home';
		$data['sub_judul']	= 'Transaksi';
		$data['user'] 		= 'MANAJER';
		$data['base_link'] 	= '';
		$data['content'] 	= 'manajer/v_transaksi';

		$this->load->view('v_home',$data);			
	}

	function json()
	 {
        header('Content-Type: application/json');
        echo $this->M_transaksi->json();
	}
	
	function detail_harga($id)
	{
		
		$this->M_security->cek_security();
		$data['judul']		= 'Transaksi';
		$data['sub_judul']	= 'Detail Transaksi';
		$data['user'] 		= 'MANAJER';
		$data['base_link'] 	= '';
		$data['content'] 	= 'manajer/v_detail_transaksi';

		$data['detail_transaksi'] 	= $this->M_transaksi->data_detail($id);
		$data['kode_transaksi'] 	= $id;
		$data['tgl_transaksi']		= $this->M_transaksi->tanggal_transaksi($id);
		$data['nama_pemesan']		= $this->M_transaksi->nama_pemesan($id);
		$data['total_harga']		= $this->M_transaksi->total_harga($id);
		$data['nama_pegawai']		= $this->M_transaksi->nama_pegawai($id);

		$this->load->view('v_home',$data);

		
	}

	function struk_pdf($id)
	{
		$this->load->library('pdf');
		$data['detail_transaksi'] 	= $this->M_transaksi->data_detail($id);
		$data['kode_transaksi'] 	= $id;
		$data['tgl_transaksi']		= $this->M_transaksi->tanggal_transaksi($id);
		$data['nama_pemesan']		= $this->M_transaksi->nama_pemesan($id);
		$data['total_harga']		= $this->M_transaksi->total_harga($id);
		$data['nama_pegawai']		= $this->M_transaksi->nama_pegawai($id);

		
		

		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "struk-transaksi.pdf";
		$this->pdf->load_view('struk_pdf', $data);
		
	}



	public function validasi_data()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_pemesanan') == '')
		{
			$data['inputerror'][] = 'id_pemesanan';
			$data['error_string'][] = 'ID pemesanan tidak boleh kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_makanan') == '')
		{
			$data['inputerror'][] = 'id_makanan';
			$data['error_string'][] = 'ID Makanan tidak boleh kosong';
			$data['status'] = FALSE;
		}



		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}

/* End of file Transaksi.php */
/* Location: ./application/controllers/manajer/Transaksi.php */