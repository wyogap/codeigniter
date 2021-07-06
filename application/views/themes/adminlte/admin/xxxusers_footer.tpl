<script type="text/javascript">
    var editor_pwd;

    $(document).ready(function() {
        editor_pwd = new $.fn.dataTable.Editor( {   
            ajax: "{$site_url}auth/resetpassword",
            table: "#{$crud.table_id}",
            idSrc: "user_id",
            fields: [ 
            {
                label: "User Id:",
                name: "user_id",
                type: "hidden",
            }, {
                label: "Nama:",
                name: "nama",
                type: "readonly",
            }, {
                label: "Username:",
                name: "user_name",
                type: "readonly",
            }, {
                label: "Password Baru:",
                name: "pwd1",
                type: "password",
            }, {
                label: "Password Baru (Lagi):",
                name: "pwd2",
                type: "password",
            }
            ],
            i18n: {
                edit: {
                    button: "Reset Password",
                    title:  "Ubah password pengguna",
                    submit: "Simpan"
                },
                error: {
                    system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi admin sistem."
                },
            }
        });

        editor_pwd.on('open', function () {
            $('div.DTE_Body').after( $('div.DTE_Footer') );
        });

        editor_pwd.on('preSubmit', function(e, o, action) {
            if (action === 'edit') {
                let field = null;

                let field1 = this.field('pwd1');
                if ( ! field1.isMultiValue() ) {
					if ( ! field1.val() ) {
						field1.error( 'Harus diisi' );
					}
				}
        
                let field2 = this.field('pwd2');
                if ( ! field2.isMultiValue() ) {
					if ( ! field2.val() ) {
						field2.error( 'Harus diisi' );
					}
				}

                if (field1.val() != field2.val()) {
                    field2.error('Password baru tidak sama');
                }

                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    return false;
                }
            }

        });

    });

    function reset_password(e, dt, node, conf) {
        editor_pwd
            .title("Ubah Password pengguna")
            .buttons([
                { label: "Simpan", className: "btn-primary", fn: function () { this.submit(); } },
            ])
            .edit(dt.row({ selected: true }).index(), true);
    }

</script>
