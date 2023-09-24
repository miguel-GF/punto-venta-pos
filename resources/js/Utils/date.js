//Método para obtener fecha actual como jueves, 17 de agosto de 2023
export const obtenerFechaActualCompletaMostrar = () => {
  let fecha = new Date();
  let opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
  return fecha.toLocaleDateString('es-MX', opciones);
};

//Método para obtener fecha actual como DD/MM/YYYY
export const obtenerFechaActualMostrar = () => {
  let fecha = new Date();
  let opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
  return fecha.toLocaleDateString('es-MX', opciones);
};

//Método para obtener fecha actual como YYYY/MM/DD
export const obtenerFechaActualOperacion = () => {
  let fecha = new Date();
  let anio = fecha.getFullYear();
  let mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
  let dia = ("0" + fecha.getDate()).slice(-2);
  return anio + "-" + mes + "-" + dia;
};

// Método para obtener fecha actual como YYYY/MM/DD HH:mm:ss
export const obtenerFechaHoraActualOperacion = () => {
  let fecha = new Date();
  let anio = fecha.getFullYear();
  let mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
  let dia = ("0" + fecha.getDate()).slice(-2);
  let horas = ("0" + fecha.getHours()).slice(-2);
  let minutos = ("0" + fecha.getMinutes()).slice(-2);
  let segundos = ("0" + fecha.getSeconds()).slice(-2);
  
  return `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
};

// Formatear fhecah como YYYY/MMM/DD HH:mm:ss
export const obtenerFechaHoraLeible = (fechaParam = null, conHoras = true) => {
  const meses = [
    "Ene", "Feb", "Mar", "Abr", "May", "Jun",
    "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
  ];

  const fecha = fechaParam ? new Date(fechaParam) : new Date();
  const anio = fecha.getFullYear();
  const mes = meses[fecha.getMonth()];
  const dia = ("0" + fecha.getDate()).slice(-2);
  const horas = ("0" + fecha.getHours()).slice(-2);
  const minutos = ("0" + fecha.getMinutes()).slice(-2);
  const segundos = ("0" + fecha.getSeconds()).slice(-2);

  if (conHoras) {
    return `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
  }

  return `${anio}-${mes}-${dia}`;
};