import { RotatingLines } from "react-loader-spinner";
import React from "react"

export default function Loader() {
  return (
    <RotatingLines
      strokeColor="grey"
      strokeWidth="5"
      animationDuration="0.75"
      width="96"
      visible={true}
    />
  )
}
