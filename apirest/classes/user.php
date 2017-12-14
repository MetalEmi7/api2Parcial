<?php
class User
{
	public $id;
	public $email;
	public $password;
	public $rol;
	public $photo;

	public function deleteUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				delete 
				from users 				
				WHERE id=:id");
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
	}



	//STATIC
	public static function borrarUsuario($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta = $objetoAccesoDato->RetornarConsulta("delete from users where id=:id");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);
		//En este caso se refiere a un valor, en este caso, referido a un 'ID' que no es de instancia.
		$consulta->execute();
		
		return $consulta->rowCount();
	}




	public function updateUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update users
				email =:email,
				password =:password,
				rol =:rol,
				photo = :photo,
				WHERE id =:id");
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
		$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
		$consulta->bindValue(':photo', $this->photo, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public function insertUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into users 
				(email,password,photo,rol)
				values (:email,:password,:photo,:rol)");
		$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
		$consulta->bindValue(':photo', $this->photo, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


	public static function getAllUsers()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "user");
	}

	public static function getUserById($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where id = :id");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);
		$consulta->execute();
		$user = $consulta->fetchObject('User');
		return $user;
	}

	public static function getUserDataByEmailAndPassword($email, $password)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where email = :email and password = :password");
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->fetchObject('User');
	}

	public static function userAlreadyExist($email)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where email = :email");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->rowCount() > 0;
	}
}