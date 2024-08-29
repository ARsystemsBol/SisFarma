<?php
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUser($user, $clave) {
        // Consulta preparada para evitar inyecciones SQL
        $stmt = $this->db->prepare("SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.idsucursal AS idsuc,
                                     r.idrol, r.rol , s.nombre AS dessucursal, c.nombrecaja, c.id AS idcaja, s.almacen,
                                     ec.descripcion AS descaja
                                     FROM usuario u 
                                     INNER JOIN rol r ON u.rol = r.idrol 
                                     INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
                                     INNER JOIN caja c ON  u.idusuario = c.idusuario
                                     INNER JOIN estado_caja ec ON c.estado = ec.id 
                                     WHERE u.usuario = ? AND u.clave = ?");
        $stmt->bind_param("ss", $user, $clave);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
