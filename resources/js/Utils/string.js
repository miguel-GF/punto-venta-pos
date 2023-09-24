import { sample } from './lodash';
const numeros = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0']
const caracteresEspeciales = ['@', '$', '%', '&', '(', ')', '|', '?', '#', '-', '_']
const alpha = Array.from(Array(26)).map((e, i) => i + 65)
const alphabet = alpha.flatMap((x) => [
  String.fromCharCode(x),
  String.fromCharCode(x).toLowerCase()
])

export const generarPassword = (cantidad = 10) => {
  let valores = [], pass = "";
  const conjunto = valores.concat(numeros, caracteresEspeciales, alphabet)
  for (let index = 0; index < cantidad; index++) {
    pass = pass.concat(sample(conjunto))
  }
  return pass;
}
