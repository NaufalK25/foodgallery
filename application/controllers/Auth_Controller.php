<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Auth_Controller extends CI_Controller {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('User');
        }

        public function index()
        {   
            if(!$this->session->username)
            {
                // username
                $this->form_validation->set_rules('username', 'Username', 'required|trim', [
                    'required' => '{field} tidak boleh kosong!',
                ]);
                // password
                $this->form_validation->set_rules('password', 'Password', 'required|trim', [
                    'required' => '{field} tidak boleh kosong!',
                ]);
                
                if($this->form_validation->run() == false)
                {
                    $data = [
                        'page_title' => 'Login | FoodGallery',
                        'url' => base_url() . 'login'
                    ];
        
                    $this->load->view('templates/header', $data);
                    $this->load->view('auth/login');
                    $this->load->view('templates/script-footer');
                }
                else
                {
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');
    
                    $user = $this->User->get_username($username);
    
                    if($user)
                    {
                        if(password_verify($password, $user['password']))
                        {
                            $data = [
                                'username' => $user['username']
                            ];
    
                            $this->session->set_userdata($data);

                            $this->session->set_flashdata(
                                'message',
                                '<div class="alert alert-success alert-dismissible d-flex align-items-center fade show alert-recipe" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                    <div class="login-alert">
                                        Selamat datang ' . $data['username'] . ', silakan mencari resep yang anda inginkan!
                                    </div>
                                <button type="button" class="btn-close alert-button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>'
                            );

                            redirect();
                        }
                        else
                        {
                            $this->session->set_flashdata(
                                'message',
                                '<div class="alert alert-danger alert-dismissible d-flex align-items-center fade show" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div class="login-alert">
                                        Password anda salah!
                                    </div>
                                <button type="button" class="btn-close alert-button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>'
                            );
                            redirect('login');
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata(
                            'message',
                            '<div class="alert alert-danger alert-dismissible d-flex align-items-center fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div class="login-alert">
                                    Username belum terdaftar!
                                </div>
                            <button type="button" class="btn-close alert-button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>'
                        );
                        redirect('login');
                    }
                }
            }
            else
            {
                redirect('error');
            }
        }

        public function register()
        {
            if(!$this->session->username)
            {
                // fullname
                $this->form_validation->set_rules('fullname', 'Nama', 'required|trim', [
                    'required' => '{field} tidak boleh kosong!'
                ]);
                // username
                $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]', [
                    'required' => '{field} tidak boleh kosong!',
                    'is_unique' => '{field} telah terdaftar!'
                ]);
                // password
                $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
                    'required' => '{field} tidak boleh kosong!',
                    'matches' => '{field} tidak cocok!',
                    'min_length' => '{field} terlalu pendek!'
                ]);
                // confirm password
                $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]', [
                    'required' => 'Konfirmasi {field} tidak boleh kosong!',
                    'matches' => '{field} tidak cocok!'
                ]);
    
                if($this->form_validation->run( ) == false)
                {
                    $data = [
                        'page_title' => 'Register | FoodGallery',
                        'url' => base_url() . 'register'
                    ];
        
                    $this->load->view('templates/header', $data);
                    $this->load->view('auth/register');
                    $this->load->view('templates/script-footer');
                }
                else
                {
                    $data = [
                        'username' => htmlspecialchars($this->input->post('username', true)),
                        'fullname' => htmlspecialchars($this->input->post('fullname', true)),
                        'image' => 'default_user.png',
                        'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                        'date_created' => time()
                    ];
    
                    $this->User->add_new_user($data);
    
                    $this->session->set_flashdata(
                        'message',
                        '<div class="alert alert-success alert-dismissible d-flex align-items-center fade show" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                            <div class="login-alert">
                                Selamat ' . $data['username'] . ', akun anda berhasil dibuat! Silakan login
                            </div>
                        <button type="button" class="btn-close alert-button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                    );
                    redirect('login');
                }
            }
            else
            {
                redirect('error');
            }
        }

        public function logout()
        {
            if(!$this->session->username)
            {
                redirect('error');
            }
            else
            {
                $this->session->unset_userdata($this->session->username);
                $this->session->sess_destroy();
                redirect();
            }
        }
    }
?>