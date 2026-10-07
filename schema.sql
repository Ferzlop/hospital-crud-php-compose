CREATE TABLE IF NOT EXISTS pacientes (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  enfermedad VARCHAR(150) NOT NULL,
  es_anomalia VARCHAR(2) NOT NULL CHECK (es_anomalia IN ('si', 'no')),
  medicina VARCHAR(150) DEFAULT 'No especificada',
  foto VARCHAR(255),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pacientes de ejemplo para probar los reportes
INSERT INTO pacientes (nombre, enfermedad, es_anomalia, medicina, foto) VALUES
('Herman, el conserje', 'Mutación celular acelerada', 'si', 'Suero de contención Nivel 3', ''),
('Rosa Gómez', 'Gripe común', 'no', 'Paracetamol', '');
